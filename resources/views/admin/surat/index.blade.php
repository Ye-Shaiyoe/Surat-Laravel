@extends('layouts.admin')
@section('title', $title ?? 'Antrian Surat')

@section('content')

<style>
    /* Filter Card */
    .filter-card {
        background: var(--bg-secondary);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }

    .filter-input-group {
        position: relative;
        display: flex;
        align-items: center;
    }

    .filter-input-group .search-icon {
        position: absolute;
        left: 12px;
        color: var(--text-secondary);
        font-size: 14px;
        pointer-events: none;
    }

    .filter-input-group input {
        width: 100%;
        padding: 9px 36px 9px 36px;
        border: 1px solid var(--border-color);
        background: var(--bg-tertiary);
        color: var(--text-primary);
        border-radius: 8px;
        font-size: 13px;
        transition: all 0.2s ease;
    }

    .filter-input-group input:focus {
        border-color: #3b82f6;
        background: var(--bg-primary);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
        outline: none;
    }

    .clear-search-btn {
        position: absolute;
        right: 12px;
        background: none;
        border: none;
        color: var(--text-secondary);
        cursor: pointer;
        font-size: 14px;
        padding: 0;
        display: none;
        align-items: center;
        justify-content: center;
    }

    .clear-search-btn:hover {
        color: #ef4444;
    }

    .filter-select {
        width: 100%;
        padding: 7px 10px;
        border: 1px solid var(--border-color);
        background: var(--bg-tertiary);
        color: var(--text-primary);
        border-radius: 8px;
        font-size: 12.5px;
        transition: all 0.2s;
    }

    .filter-select:focus {
        border-color: #3b82f6;
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .filter-label {
        font-size: 11px;
        font-weight: 600;
        color: var(--text-secondary);
        display: block;
        margin-bottom: 4px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Quick Filter Chips */
    .quick-chips-wrapper {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        padding-bottom: 2px;
        margin-top: 12px;
    }

    .chip-btn {
        border: 1px solid var(--border-color);
        background: var(--bg-tertiary);
        color: var(--text-secondary);
        font-size: 12px;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .chip-btn:hover {
        border-color: #3b82f6;
        color: #3b82f6;
        background: var(--bg-primary);
    }

    .chip-btn.active {
        background: #2563eb;
        color: white;
        border-color: #2563eb;
    }

    /* Table Container loading effect */
    #tableContainer {
        position: relative;
        transition: opacity 0.2s ease;
    }

    #tableContainer.loading {
        opacity: 0.5;
        pointer-events: none;
    }

    .live-indicator {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 11.5px;
        color: #10b981;
        font-weight: 600;
    }

    .live-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #10b981;
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        animation: pulse-green 1.8s infinite;
    }

    @keyframes pulse-green {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }

    .loading-spinner {
        display: none;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 10;
        background: rgba(15, 23, 42, 0.8);
        color: white;
        padding: 9px 18px;
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 600;
        backdrop-filter: blur(4px);
    }
</style>

{{-- FILTER SECTION --}}
<div class="filter-card">
    <form id="filterSuratForm" method="GET" action="{{ url()->current() }}" onsubmit="return false;">

        {{-- Top Row: Search & Reset --}}
        <div class="row g-3 align-items-center mb-3">
            <div class="col-lg-8 col-md-8">
                <label class="filter-label">
                    <i class="bi bi-search me-1"></i> Pencarian
                </label>
                <div class="filter-input-group">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" id="liveSearchInput" name="search" value="{{ request('search') }}"
                           placeholder="Cari judul surat, nomor surat, nama pengusul, atau tujuan..."
                           autocomplete="off">
                    <button type="button" id="clearSearchBtn" class="clear-search-btn" title="Hapus kata kunci">
                        <i class="bi bi-x-circle-fill"></i>
                    </button>
                </div>
            </div>

            <div class="col-lg-4 col-md-4 d-flex justify-content-md-end align-items-end gap-2 pt-md-4 flex-wrap">
                <div class="live-indicator me-1 d-none d-sm-inline-flex">
                    <span class="live-dot"></span> Live Filter
                </div>
                <a id="btnExportExcel" href="{{ route('admin.surat.exportExcel', request()->all()) }}" class="btn btn-sm btn-success px-3 py-2 d-inline-flex align-items-center gap-1" style="border-radius:8px; font-size:12px; font-weight:600; background:#10b981; border-color:#10b981;" title="Export Excel data surat sesuai filter">
                    <i class="bi bi-file-earmark-excel-fill"></i> Export Excel
                </a>
                <button type="button" onclick="resetAllFilters()" class="btn btn-sm btn-light border px-3 py-2" style="border-radius:8px; font-size:12px; font-weight:600;">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Filter
                </button>
            </div>
        </div>

        {{-- Filter Controls Grid --}}
        <div class="row g-2">
            {{-- Status Surat --}}
            @if(!isset($title) || in_array($title, ['Antrian Surat', 'Semua Surat']))
            <div class="col-lg-2 col-md-4 col-6">
                <label class="filter-label">Status</label>
                <select name="status" class="filter-select">
                    <option value="">Semua Status</option>
                    <option value="proses"       {{ request('status') === 'proses'       ? 'selected' : '' }}>Proses</option>
                    <option value="revisi"       {{ request('status') === 'revisi'       ? 'selected' : '' }}>Revisi (User)</option>
                    <option value="revisi_admin" {{ request('status') === 'revisi_admin' ? 'selected' : '' }}>Admin Revisi</option>
                    <option value="selesai"      {{ request('status') === 'selesai'      ? 'selected' : '' }}>Selesai</option>
                    <option value="ditolak"      {{ request('status') === 'ditolak'      ? 'selected' : '' }}>Ditolak</option>
                    <option value="draft"        {{ request('status') === 'draft'        ? 'selected' : '' }}>Draf</option>
                </select>
            </div>
            @endif

            {{-- Jenis Surat --}}
            <div class="col-lg-2 col-md-4 col-6">
                <label class="filter-label">Jenis Surat</label>
                <select name="jenis" class="filter-select">
                    <option value="">Semua Jenis</option>
                    @foreach(\App\Models\Surat::JENIS_LABEL as $val => $label)
                        <option value="{{ $val }}" {{ request('jenis') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Sifat Surat --}}
            <div class="col-lg-2 col-md-4 col-6">
                <label class="filter-label">Sifat Surat</label>
                <select name="sifat" class="filter-select">
                    <option value="">Semua Sifat</option>
                    <option value="biasa"   {{ request('sifat') === 'biasa'   ? 'selected' : '' }}>Biasa</option>
                    <option value="segera"  {{ request('sifat') === 'segera'  ? 'selected' : '' }}>Segera</option>
                    <option value="rahasia" {{ request('sifat') === 'rahasia' ? 'selected' : '' }}>Rahasia</option>
                </select>
            </div>

            {{-- Tahap Surat --}}
            <div class="col-lg-2 col-md-4 col-6">
                <label class="filter-label">Tahap Proses</label>
                <select name="tahap" class="filter-select">
                    <option value="">Semua Tahap</option>
                    @foreach(\App\Models\Surat::NAMA_TAHAP as $no => $nama)
                        <option value="{{ $no }}" {{ request('tahap') == $no ? 'selected' : '' }}>{{ $no }}. {{ $nama }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Pengusul (Pegawai) --}}
            @if(isset($users) && $users->isNotEmpty())
            <div class="col-lg-2 col-md-4 col-6">
                <label class="filter-label">Pengusul</label>
                <select name="user_id" class="filter-select">
                    <option value="">Semua Pengusul</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            {{-- Dari Tanggal --}}
            <div class="col-lg-2 col-md-3 col-6">
                <label class="filter-label">Dari Tanggal</label>
                <input type="date" name="tanggal_mulai" id="tanggalMulaiInput" value="{{ request('tanggal_mulai') }}" class="filter-select">
            </div>

            {{-- Sampai Tanggal --}}
            <div class="col-lg-2 col-md-3 col-6">
                <label class="filter-label">Sampai Tanggal</label>
                <input type="date" name="tanggal_selesai" id="tanggalSelesaiInput" value="{{ request('tanggal_selesai') }}" class="filter-select">
            </div>

            {{-- Tahun --}}
            <div class="col-lg-1 col-md-2 col-6">
                <label class="filter-label">Tahun</label>
                <select name="tahun" class="filter-select">
                    <option value="">Semua</option>
                    @php $startYear = 2024; $currentYear = date('Y'); @endphp
                    @for($y = $currentYear; $y >= $startYear; $y--)
                        <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            {{-- Bulan --}}
            <div class="col-lg-1 col-md-2 col-6">
                <label class="filter-label">Bulan</label>
                <select name="bulan" class="filter-select">
                    <option value="">Semua</option>
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('M') }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Quick Filter Chips --}}
        <div class="quick-chips-wrapper">
            <button type="button" class="chip-btn {{ !request()->anyFilled(['status', 'sifat', 'sla_status']) ? 'active' : '' }}" onclick="applyQuickFilter('', '')">
                <i class="bi bi-grid-fill"></i> Semua
            </button>
            <button type="button" class="chip-btn {{ request('status') === 'proses' ? 'active' : '' }}" onclick="applyQuickFilter('status', 'proses')">
                <i class="bi bi-hourglass-split"></i> Diproses
            </button>
            <button type="button" class="chip-btn {{ request('status') === 'revisi' ? 'active' : '' }}" onclick="applyQuickFilter('status', 'revisi')">
                <i class="bi bi-pencil-square"></i> Perlu Revisi
            </button>
            <button type="button" class="chip-btn {{ request('status') === 'selesai' ? 'active' : '' }}" onclick="applyQuickFilter('status', 'selesai')">
                <i class="bi bi-check-circle-fill"></i> Selesai
            </button>
            <button type="button" class="chip-btn {{ request('sifat') === 'segera' ? 'active' : '' }}" onclick="applyQuickFilter('sifat', 'segera')">
                <i class="bi bi-lightning-charge-fill"></i> Segera
            </button>
            <button type="button" class="chip-btn {{ request('sla_status') === 'terlambat' ? 'active' : '' }}" onclick="applyQuickFilter('sla_status', 'terlambat')">
                <i class="bi bi-exclamation-triangle-fill"></i> SLA Terlambat
            </button>
        </div>

        {{-- Hidden fields for quick filters or sorting if needed --}}
        <input type="hidden" name="sla_status" id="slaStatusInput" value="{{ request('sla_status') }}">
        <input type="hidden" name="sort" id="sortInput" value="{{ request('sort', 'priority') }}">
    </form>
</div>

{{-- TABEL CONTAINER --}}
<div class="card" id="tableContainer">
    <div id="tableSpinner" class="loading-spinner">
        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
        Memuat data...
    </div>

    @include('admin.surat.partials.table', ['surats' => $surats, 'title' => $title])
</div>

{{-- SCRIPT: LIVE SEARCH & ASYNC FILTER --}}
<script>
    let searchDebounceTimer = null;
    const searchInput = document.getElementById('liveSearchInput');
    const clearBtn = document.getElementById('clearSearchBtn');
    const filterForm = document.getElementById('filterSuratForm');
    const tableContainer = document.getElementById('tableContainer');
    const tableSpinner = document.getElementById('tableSpinner');

    // Update clear button visibility
    function updateClearBtn() {
        if (searchInput && clearBtn) {
            clearBtn.style.display = searchInput.value.length > 0 ? 'inline-flex' : 'none';
        }
    }

    // Dynamic Export Excel URL Sync
    const exportBaseUrl = "{{ route('admin.surat.exportExcel') }}";
    function updateExportUrl() {
        const btnExport = document.getElementById('btnExportExcel');
        if (!btnExport || !filterForm) return;

        const formData = new FormData(filterForm);
        const params = new URLSearchParams();

        for (const [key, value] of formData.entries()) {
            if (value !== '' && value !== null) {
                params.append(key, value);
            }
        }

        const qs = params.toString();
        btnExport.href = exportBaseUrl + (qs ? '?' + qs : '');
    }

    // Function to perform AJAX fetch and update table
    function performLiveFilter(targetUrl = null) {
        if (!filterForm || !tableContainer) return;

        updateExportUrl();

        const url = targetUrl ? new URL(targetUrl, window.location.origin) : new URL(filterForm.action, window.location.origin);
        
        if (!targetUrl) {
            const formData = new FormData(filterForm);
            const params = new URLSearchParams();
            
            for (const [key, value] of formData.entries()) {
                if (value !== '' && value !== null) {
                    params.append(key, value);
                }
            }
            url.search = params.toString();
        }

        // Show subtle loading
        tableContainer.classList.add('loading');
        if (tableSpinner) tableSpinner.style.display = 'inline-flex';

        fetch(url.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response error');
            return response.text();
        })
        .then(html => {
            tableContainer.innerHTML = `
                <div id="tableSpinner" class="loading-spinner">
                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                    Memuat data...
                </div>
                ${html}
            `;

            // Update browser URL
            window.history.pushState(null, '', url.toString());

            // Re-bind pagination links
            bindPaginationLinks();
        })
        .catch(err => {
            console.error('Filter request failed:', err);
        })
        .finally(() => {
            tableContainer.classList.remove('loading');
            const spinner = document.getElementById('tableSpinner');
            if (spinner) spinner.style.display = 'none';
        });
    }

    // Bind AJAX to pagination links
    function bindPaginationLinks() {
        document.querySelectorAll('#tableContainer .ajax-pagination a, #tableContainer .pagination a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const pageUrl = this.getAttribute('href');
                if (pageUrl) {
                    performLiveFilter(pageUrl);
                    tableContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    }

    // Apply Quick Filter Chips
    function applyQuickFilter(field, value) {
        if (!field) {
            const statusSel = filterForm.querySelector('select[name="status"]');
            const sifatSel = filterForm.querySelector('select[name="sifat"]');
            const slaInput = document.getElementById('slaStatusInput');
            if (statusSel) statusSel.value = '';
            if (sifatSel) sifatSel.value = '';
            if (slaInput) slaInput.value = '';
        } else if (field === 'status') {
            const statusSel = filterForm.querySelector('select[name="status"]');
            if (statusSel) statusSel.value = value;
        } else if (field === 'sifat') {
            const sifatSel = filterForm.querySelector('select[name="sifat"]');
            if (sifatSel) sifatSel.value = value;
        } else if (field === 'sla_status') {
            const slaInput = document.getElementById('slaStatusInput');
            if (slaInput) slaInput.value = value;
        }

        // Update active chip classes
        document.querySelectorAll('.chip-btn').forEach(btn => btn.classList.remove('active'));
        if (event && event.currentTarget) {
            event.currentTarget.classList.add('active');
        }

        performLiveFilter();
    }

    // Reset All Filters
    function resetAllFilters() {
        if (!filterForm) return;
        
        filterForm.reset();
        if (searchInput) searchInput.value = '';
        
        filterForm.querySelectorAll('select').forEach(sel => sel.value = '');
        filterForm.querySelectorAll('input[type="date"]').forEach(inp => inp.value = '');
        const slaInput = document.getElementById('slaStatusInput');
        if (slaInput) slaInput.value = '';
        
        updateClearBtn();
        updateExportUrl();
        
        document.querySelectorAll('.chip-btn').forEach((btn, idx) => {
            btn.classList.toggle('active', idx === 0);
        });

        performLiveFilter();
    }

    // Event Listeners on Load
    document.addEventListener('DOMContentLoaded', function() {
        updateClearBtn();
        updateExportUrl();
        bindPaginationLinks();

        // Realtime Search on Input (Debounce 300ms)
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                updateClearBtn();
                clearTimeout(searchDebounceTimer);
                searchDebounceTimer = setTimeout(() => {
                    performLiveFilter();
                }, 300);
            });
        }

        // Clear Search Button
        if (clearBtn) {
            clearBtn.addEventListener('click', function() {
                if (searchInput) {
                    searchInput.value = '';
                    searchInput.focus();
                    updateClearBtn();
                    performLiveFilter();
                }
            });
        }

        // Dropdowns & Date inputs onChange
        if (filterForm) {
            filterForm.querySelectorAll('.filter-select').forEach(select => {
                select.addEventListener('change', function() {
                    performLiveFilter();
                });
            });
        }

        // Handle browser Back/Forward navigation
        window.addEventListener('popstate', function() {
            window.location.reload();
        });
    });

    // Support Turbo if loaded
    document.addEventListener('turbo:load', function() {
        updateClearBtn();
        updateExportUrl();
        bindPaginationLinks();
    });
</script>

@endsection