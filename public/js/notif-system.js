(function () {
    'use strict';

    // ── Konfigurasi ──────────────────────────────────────────────────────
    const POLL_INTERVAL = 30000;   // 30 detik
    const TOAST_DURATION = 6000;   // auto-dismiss 6 detik
    const MAX_TOAST = 3;           // max toast tampil bersamaan

    // URL endpoint (di-set dari layout via window.NOTIF_CONFIG)
    const cfg = window.NOTIF_CONFIG || {};
    const URL_POLL      = cfg.urlPoll      || '/notif/poll';
    const URL_READ      = cfg.urlRead      || '/notif/read/';
    const URL_READ_ALL  = cfg.urlReadAll   || '/notif/read-all';
    const URL_DELETE    = cfg.urlDelete    || '/notif/delete/';
    const URL_DELETE_ALL= cfg.urlDeleteAll || '/notif/delete-all';
    const CSRF          = cfg.csrf         || document.querySelector('meta[name=csrf-token]')?.content || '';

    let lastFetch = null;
    let pollTimer = null;
    let toastCount = 0;
    let bellBadgeEl = null;
    let dropdownListEl = null;

    // ── Init ─────────────────────────────────────────────────────────────
    function init() {
        injectStyles();
        createToastContainer();

        bellBadgeEl    = document.getElementById('notif-badge-count');
        dropdownListEl = document.getElementById('notif-dropdown-list');

        // Pertama kali: langsung fetch
        fetchNotifs(true);

        // Polling interval
        pollTimer = setInterval(() => fetchNotifs(false), POLL_INTERVAL);

        // Tombol hapus semua
        const btnDelAll = document.getElementById('notif-delete-all');
        if (btnDelAll) {
            btnDelAll.addEventListener('click', () => {
                if (!confirm('Hapus semua notifikasi?')) return;
                apiPost(URL_DELETE_ALL);
                if (dropdownListEl) dropdownListEl.innerHTML = emptyHtml();
                updateBadge(0);
            });
        }

        // Tombol tandai semua dibaca
        const btnReadAll = document.getElementById('notif-read-all');
        if (btnReadAll) {
            btnReadAll.addEventListener('click', () => {
                apiPost(URL_READ_ALL);
                document.querySelectorAll('.notif-drop-item.unread')
                        .forEach(el => el.classList.remove('unread'));
                updateBadge(0);
            });
        }
    }

    // ── Fetch notifikasi ──────────────────────────────────────────────────
    function fetchNotifs(isFirst) {
        const url = lastFetch && !isFirst
            ? `${URL_POLL}?since=${encodeURIComponent(lastFetch)}`
            : URL_POLL;

        fetch(url, { headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(data => {
                lastFetch = data.server_time;
                updateBadge(data.unread_count);

                if (isFirst) {
                    // Render dropdown list
                    renderDropdown(data.notifications);
                } else {
                    // Hanya notif baru → tampilkan toast
                    data.notifications.forEach(n => {
                        if (!n.read) showToast(n);
                    });
                    // Update dropdown jika ada notif baru
                    if (data.notifications.length > 0) {
                        prependDropdown(data.notifications);
                    }
                }
            })
            .catch(() => {}); // silent fail jika offline
    }

    // ── Update badge angka di bell ────────────────────────────────────────
    function updateBadge(count) {
        if (!bellBadgeEl) return;
        if (count > 0) {
            bellBadgeEl.textContent = count > 99 ? '99+' : count;
            bellBadgeEl.style.display = 'flex';
        } else {
            bellBadgeEl.style.display = 'none';
        }
    }

    // ── Render daftar notifikasi di dropdown ──────────────────────────────
    function renderDropdown(notifs) {
        if (!dropdownListEl) return;
        if (!notifs.length) {
            dropdownListEl.innerHTML = emptyHtml();
            return;
        }
        dropdownListEl.innerHTML = notifs.map(notifItemHtml).join('');
        bindDropdownActions();
    }

    function prependDropdown(notifs) {
        if (!dropdownListEl) return;
        const emptyEl = dropdownListEl.querySelector('.notif-empty');
        if (emptyEl) dropdownListEl.innerHTML = '';

        notifs.forEach(n => {
            const div = document.createElement('div');
            div.innerHTML = notifItemHtml(n);
            dropdownListEl.insertBefore(div.firstElementChild, dropdownListEl.firstChild);
        });
        bindDropdownActions();
    }

    function notifItemHtml(n) {
        const icons = { success:'✅', danger:'❌', warning:'⚠️', info:'📨' };
        const icon  = icons[n.type] || '🔔';
        return `
        <div class="notif-drop-item ${n.read ? '' : 'unread'}" data-id="${n.id}">
            <div class="notif-drop-icon ${n.type}">${icon}</div>
            <div class="notif-drop-body">
                <div class="notif-drop-title">${escHtml(n.title)}</div>
                <div class="notif-drop-msg">${escHtml(n.message)}</div>
                <div class="notif-drop-time">${n.time}</div>
            </div>
            <div class="notif-drop-actions">
                ${n.url ? `<a href="${n.url}" class="notif-drop-link" data-id="${n.id}">Lihat</a>` : ''}
                <button class="notif-drop-del" data-id="${n.id}" title="Hapus">×</button>
            </div>
        </div>`;
    }

    function bindDropdownActions() {
        // Klik "Lihat" → mark as read
        dropdownListEl?.querySelectorAll('.notif-drop-link').forEach(el => {
            el.addEventListener('click', () => {
                const id = el.dataset.id;
                apiPost(URL_READ + id);
                el.closest('.notif-drop-item')?.classList.remove('unread');
            });
        });

        // Klik × → hapus
        dropdownListEl?.querySelectorAll('.notif-drop-del').forEach(el => {
            el.addEventListener('click', (e) => {
                e.stopPropagation();
                const id   = el.dataset.id;
                const item = el.closest('.notif-drop-item');
                apiPost(URL_DELETE + id);
                item?.classList.add('notif-removing');
                setTimeout(() => {
                    item?.remove();
                    if (!dropdownListEl.children.length) {
                        dropdownListEl.innerHTML = emptyHtml();
                    }
                }, 250);
            });
        });
    }

    function emptyHtml() {
        return `<div class="notif-empty">
            <span style="font-size:28px;display:block;margin-bottom:6px;">🔔</span>
            Belum ada notifikasi
        </div>`;
    }

    // ── Toast popup ────────────────────────────────────────────────────────
    function showToast(n) {
        if (toastCount >= MAX_TOAST) return;

        const colors = {
            success: { bg:'#f0fdf4', border:'#86efac', icon:'✅', accent:'#15803d' },
            danger:  { bg:'#fef2f2', border:'#fca5a5', icon:'❌', accent:'#b91c1c' },
            warning: { bg:'#fffbeb', border:'#fcd34d', icon:'⚠️', accent:'#b45309' },
            info:    { bg:'#eff6ff', border:'#93c5fd', icon:'📨', accent:'#1d4ed8' },
        };
        const c = colors[n.type] || colors.info;

        const toast = document.createElement('div');
        toast.className = 'notif-toast notif-toast-enter';
        toast.dataset.id = n.id;
        toast.innerHTML = `
            <div class="notif-toast-inner" style="background:${c.bg};border-left:4px solid ${c.border};">
                <div class="notif-toast-icon">${c.icon}</div>
                <div class="notif-toast-content">
                    <div class="notif-toast-title" style="color:${c.accent};">${escHtml(n.title)}</div>
                    <div class="notif-toast-msg">${escHtml(n.message)}</div>
                    ${n.url ? `<a href="${n.url}" class="notif-toast-cta" style="color:${c.accent};" data-id="${n.id}">Lihat detail →</a>` : ''}
                </div>
                <button class="notif-toast-close" title="Tutup">×</button>
            </div>
            <div class="notif-toast-progress" style="background:${c.border};"></div>
        `;

        document.getElementById('notif-toast-container').appendChild(toast);
        toastCount++;

        // Animate in
        requestAnimationFrame(() => {
            requestAnimationFrame(() => toast.classList.add('notif-toast-visible'));
        });

        // Progress bar countdown
        const bar = toast.querySelector('.notif-toast-progress');
        bar.style.transition = `width ${TOAST_DURATION}ms linear`;
        requestAnimationFrame(() => { bar.style.width = '0%'; });

        // Auto dismiss
        const timer = setTimeout(() => dismissToast(toast), TOAST_DURATION);

        // Manual dismiss
        toast.querySelector('.notif-toast-close').addEventListener('click', () => {
            clearTimeout(timer);
            dismissToast(toast);
        });

        // Klik "Lihat detail" → mark as read
        toast.querySelector('.notif-toast-cta')?.addEventListener('click', () => {
            apiPost(URL_READ + n.id);
        });

        // Pause on hover
        toast.addEventListener('mouseenter', () => bar.style.transitionProperty = 'none');
        toast.addEventListener('mouseleave', () => {
            bar.style.transition = `width 2000ms linear`;
            bar.style.width = '0%';
        });
    }

    function dismissToast(toast) {
        toast.classList.remove('notif-toast-visible');
        toast.classList.add('notif-toast-leave');
        setTimeout(() => { toast.remove(); toastCount--; }, 350);
    }

    function createToastContainer() {
        const el = document.createElement('div');
        el.id = 'notif-toast-container';
        document.body.appendChild(el);
    }

    // ── Helper ────────────────────────────────────────────────────────────
    function apiPost(url) {
        fetch(url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json',
                       'Content-Type': 'application/json' },
        }).catch(() => {});
    }

    function escHtml(str) {
        return String(str ?? '')
            .replace(/&/g,'&amp;').replace(/</g,'&lt;')
            .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    // ── CSS inject ────────────────────────────────────────────────────────
    function injectStyles() {
        const css = `
        /* ── Toast container ── */
        #notif-toast-container {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 99999;
            display: flex;
            flex-direction: column-reverse;
            gap: 10px;
            width: 340px;
            pointer-events: none;
        }

        /* ── Toast card ── */
        .notif-toast {
            pointer-events: all;
            opacity: 0;
            transform: translateX(120%);
            transition: opacity .3s ease, transform .3s cubic-bezier(.34,1.2,.64,1);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0,0,0,0.12), 0 2px 8px rgba(0,0,0,0.08);
        }
        .notif-toast.notif-toast-visible {
            opacity: 1;
            transform: translateX(0);
        }
        .notif-toast.notif-toast-leave {
            opacity: 0;
            transform: translateX(120%);
            transition: opacity .3s ease, transform .3s ease;
        }
        .notif-toast-inner {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 14px 14px 10px;
            background: #fff;
        }
        .notif-toast-icon {
            font-size: 20px;
            flex-shrink: 0;
            line-height: 1;
            margin-top: 1px;
        }
        .notif-toast-content { flex: 1; min-width: 0; }
        .notif-toast-title {
            font-size: 13px;
            font-weight: 700;
            line-height: 1.3;
            margin-bottom: 3px;
        }
        .notif-toast-msg {
            font-size: 12px;
            color: #6b7280;
            line-height: 1.4;
        }
        .notif-toast-cta {
            display: inline-block;
            font-size: 11px;
            font-weight: 600;
            text-decoration: none;
            margin-top: 5px;
        }
        .notif-toast-cta:hover { text-decoration: underline; }
        .notif-toast-close {
            flex-shrink: 0;
            background: none;
            border: none;
            font-size: 18px;
            line-height: 1;
            color: #9ca3af;
            cursor: pointer;
            padding: 0 2px;
            transition: color .15s;
        }
        .notif-toast-close:hover { color: #374151; }
        .notif-toast-progress {
            height: 3px;
            width: 100%;
            background: #e5e7eb;
            transition: width 6s linear;
        }

        /* ── Dropdown items ── */
        .notif-drop-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 12px 16px;
            border-bottom: 1px solid #f3f4f6;
            transition: background .1s, opacity .25s, transform .25s;
            cursor: default;
        }
        .notif-drop-item:hover { background: #f9fafb; }
        .notif-drop-item.unread { background: #eff6ff; }
        .notif-drop-item.unread:hover { background: #dbeafe; }
        .notif-drop-item.notif-removing { opacity:0; transform: translateX(20px); }

        .notif-drop-icon {
            width: 32px; height: 32px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 15px; flex-shrink: 0;
        }
        .notif-drop-icon.success { background:#dcfce7; }
        .notif-drop-icon.danger  { background:#fee2e2; }
        .notif-drop-icon.warning { background:#fef3c7; }
        .notif-drop-icon.info    { background:#dbeafe; }

        .notif-drop-body  { flex: 1; min-width: 0; }
        .notif-drop-title { font-size: 12px; font-weight: 600; color: #111827; line-height: 1.3; }
        .notif-drop-msg   { font-size: 11px; color: #6b7280; margin-top: 2px; line-height: 1.4; }
        .notif-drop-time  { font-size: 10px; color: #9ca3af; margin-top: 3px; }

        .notif-drop-actions { display: flex; align-items: center; gap: 4px; flex-shrink: 0; }
        .notif-drop-link {
            font-size: 11px; font-weight: 600;
            color: #1d4ed8; text-decoration: none;
            padding: 2px 6px; border-radius: 5px;
            border: 1px solid #bfdbfe;
            white-space: nowrap;
        }
        .notif-drop-link:hover { background: #dbeafe; }
        .notif-drop-del {
            background: none; border: none;
            font-size: 16px; color: #d1d5db;
            cursor: pointer; padding: 2px 4px;
            border-radius: 4px; line-height: 1;
            transition: color .15s, background .15s;
        }
        .notif-drop-del:hover { color: #ef4444; background: #fee2e2; }

        .notif-empty {
            text-align: center;
            padding: 32px 16px;
            color: #9ca3af;
            font-size: 13px;
        }

        @media (max-width: 480px) {
            #notif-toast-container { width: calc(100vw - 32px); right: 16px; bottom: 16px; }
        }
        `;
        const style = document.createElement('style');
        style.textContent = css;
        document.head.appendChild(style);
    }

    // ── Start ─────────────────────────────────────────────────────────────
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();