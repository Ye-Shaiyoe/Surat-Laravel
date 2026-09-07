<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Surat;
use App\Models\SuratTahapan;
use App\Models\SuratDeleteRequest;
use App\Models\User;
use App\Notifications\SuratStatusNotification;
use App\Notifications\SuratDiprosesNotification;
use App\Notifications\FileRevisiNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\HtmlSanitizer;
use App\Exports\AdminSuratExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class SuratController extends Controller
{
    public function index(Request $request, $title = 'Antrian Surat', bool $isAntrian = true)
    {
        $query = $this->buildFilteredQuery($request, $isAntrian);

        // Sorting
        $sort = $request->get('sort', 'priority');
        if ($sort === 'oldest') {
            $query->oldest();
        } elseif ($sort === 'sla') {
            $query->orderByRaw("CASE WHEN sla_status = 'terlambat' THEN 0 ELSE 1 END")
                  ->orderBy('deadline_sla', 'asc');
        } else {
            // Default: Prioritaskan status revisi, lalu tanggal terbaru
            $query->orderByRaw("CASE WHEN status = 'revisi' OR status = 'revisi_admin' THEN 0 ELSE 1 END")
                  ->latest();
        }

        $perPage = (int) $request->get('per_page', 15);
        $surats = $query->paginate($perPage)->withQueryString();

        // AJAX Request support for live search / filter without full page reload
        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return view('admin.surat.partials.table', compact('surats', 'title'))->render();
        }

        $users = User::orderBy('name')->get(['id', 'name']);

        return view('admin.surat.index', compact('surats', 'title', 'users'));
    }

    public function exportExcel(Request $request)
    {
        $query = $this->buildFilteredQuery($request, false);

        $sort = $request->get('sort', 'priority');
        if ($sort === 'oldest') {
            $query->oldest();
        } elseif ($sort === 'sla') {
            $query->orderByRaw("CASE WHEN sla_status = 'terlambat' THEN 0 ELSE 1 END")
                  ->orderBy('deadline_sla', 'asc');
        } else {
            $query->orderByRaw("CASE WHEN status = 'revisi' OR status = 'revisi_admin' THEN 0 ELSE 1 END")
                  ->latest();
        }

        $tglMulai = $request->get('tanggal_mulai') ?: $request->get('start_date');
        $tglSelesai = $request->get('tanggal_selesai') ?: $request->get('end_date');

        $dateSuffix = '';
        if ($tglMulai && $tglSelesai) {
            $dateSuffix = '_' . $tglMulai . '_sd_' . $tglSelesai;
        } elseif ($tglMulai) {
            $dateSuffix = '_dari_' . $tglMulai;
        } elseif ($tglSelesai) {
            $dateSuffix = '_sampai_' . $tglSelesai;
        } else {
            $dateSuffix = '_' . date('Y-m-d_His');
        }

        $fileName = 'Data_Surat_Admin' . $dateSuffix . '.xlsx';

        return Excel::download(new AdminSuratExport($query), $fileName);
    }

    /**
     * Build filtered query for Admin Surat table and export
     */
    protected function buildFilteredQuery(Request $request, bool $isAntrian = true)
    {
        $query = Surat::with(['user', 'pendingDeleteRequest'])->latest();

        $admin = Auth::user();

        // Filter berdasarkan role admin HANYA jika di halaman Antrian Surat (Inbox Tugas Aktif)
        // Di halaman Semua Surat / Surat Selesai / Tabel Data Surat, seluruh admin (termasuk Kasubbag TU & Kepala Balai) dapat melihat seluruh surat
        if ($isAntrian && $admin) {
            if ($admin->role === 'admin_aspirasi') {
                $query->where(function ($q) {
                    $q->where('tahap_sekarang', 2)
                        ->orWhere('tahap_sekarang', '>=', 5);
                });
            } elseif ($admin->role === 'admin_kasubbag_tu') {
                $query->where('tahap_sekarang', 3);
            } elseif ($admin->role === 'admin_kepala_balai') {
                $query->where('tahap_sekarang', 4);
            }
        }
        // admin lama (role='admin') tetap bisa lihat semua

        // Filter: Jenis Surat
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        // Filter: Status Surat
        if ($request->filled('status')) {
            if ($request->status === 'proses') {
                $query->whereIn('status', ['proses', 'revisi', 'revisi_admin']);
            } else {
                $query->where('status', $request->status);
            }
        }

        // Filter: Sifat Surat (biasa, segera, rahasia)
        if ($request->filled('sifat')) {
            $query->where('sifat', $request->sifat);
        }

        // Filter: Tahap Surat (1 - 10)
        if ($request->filled('tahap')) {
            $query->where('tahap_sekarang', (int) $request->tahap);
        }

        // Filter: Pengusul (Pegawai / User ID)
        if ($request->filled('user_id')) {
            $query->where('user_id', (int) $request->user_id);
        }

        // Filter: Status SLA
        if ($request->filled('sla_status')) {
            $query->where('sla_status', $request->sla_status);
        }

        // Filter: Pencarian Multi-Kolom (Judul, No. Surat, Pengusul, Tujuan)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', '%' . $search . '%')
                  ->orWhere('nomor_surat', 'like', '%' . $search . '%')
                  ->orWhere('tujuan', 'like', '%' . $search . '%')
                  ->orWhere('catatan_pengusul', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', '%' . $search . '%')
                         ->orWhere('email', 'like', '%' . $search . '%');
                  });
            });
        }

        // Filter: Rentang Tanggal Pengajuan (created_at)
        $startDate = $request->get('tanggal_mulai') ?: $request->get('start_date');
        $endDate = $request->get('tanggal_selesai') ?: $request->get('end_date');

        if ($startDate || $endDate) {
            $parsedStart = null;
            $parsedEnd = null;

            if ($startDate) {
                try {
                    $parsedStart = Carbon::parse($startDate)->startOfDay();
                } catch (\Exception $e) {
                    $parsedStart = null;
                }
            }

            if ($endDate) {
                try {
                    $parsedEnd = Carbon::parse($endDate)->endOfDay();
                } catch (\Exception $e) {
                    $parsedEnd = null;
                }
            }

            // Jika tanggal mulai lebih besar dari selesai, tukar urutan agar query valid
            if ($parsedStart && $parsedEnd && $parsedStart->gt($parsedEnd)) {
                $temp = $parsedStart->copy()->startOfDay();
                $parsedStart = $parsedEnd->copy()->startOfDay();
                $parsedEnd = $temp->copy()->endOfDay();
            }

            if ($parsedStart && $parsedEnd) {
                $query->whereBetween('created_at', [$parsedStart, $parsedEnd]);
            } elseif ($parsedStart) {
                $query->where('created_at', '>=', $parsedStart);
            } elseif ($parsedEnd) {
                $query->where('created_at', '<=', $parsedEnd);
            }
        } else {
            // Gunakan bulan dan tahun jika rentang tanggal tidak diisi
            if ($request->filled('bulan')) {
                $query->whereMonth('created_at', (int) $request->bulan);
            }
            if ($request->filled('tahun')) {
                $query->whereYear('created_at', (int) $request->tahun);
            }
        }

        return $query;
    }

    public function semua(Request $request)
    {
        return $this->index($request, 'Semua Surat', false);
    }

    public function masuk(Request $request)
    {
        $request->merge(['status' => 'proses']);
        return $this->index($request, 'Surat Masuk', false);
    }

    public function proses(Request $request)
    {
        $request->merge(['status' => 'proses']);
        return $this->index($request, 'Surat Sedang Diproses', false);
    }

    public function selesai(Request $request)
    {
        $request->merge(['status' => 'selesai']);
        return $this->index($request, 'Surat Selesai', false);
    }

    public function revisi(Request $request)
    {
        $request->merge(['status' => 'revisi']);
        return $this->index($request, 'Surat Perlu Revisi', false);
    }

    public function show($surat)
    {
        // Cari berdasarkan UUID dulu (standar baru)
        $suratModel = Surat::where('uuid', $surat)->first();

        // Fallback: Jika tidak ketemu dan inputnya angka, coba cari berdasarkan ID (untuk link lama)
        if (!$suratModel && is_numeric($surat)) {
            $suratModel = Surat::find($surat);
            if ($suratModel) {
                // Redirect otomatis ke URL versi UUID biar rapi
                return redirect()->route('admin.surat.show', $suratModel);
            }
        }

        if (!$suratModel) {
            abort(404, 'Surat tidak ditemukan.');
        }

        $suratModel->load(['user', 'tahapans.diprosesByUser']);
        return view('admin.surat.show', ['surat' => $suratModel]);
    }

    public function setujui(Request $request, Surat $surat)
    {
        $request->validate([
            'catatan' => 'nullable|string|max:500',
            'nomor_surat' => 'nullable|string|max:100',
            'alasan_keterlambatan' => ($surat->sla_status === 'terlambat' && !$surat->alasan_keterlambatan) ? 'required|string' : 'nullable',
        ]);

        // Validasi: apakah admin punya hak approve tahap ini?
        $admin = Auth::user();
        if (!$admin->canApproveTahap($surat->tahap_sekarang)) {
            return back()->with('error', 'Anda tidak memiliki wewenang untuk approve tahap ini.');
        }

        // Jika status revisi (dari user atau admin), set jadi 'proses' lagi
        $updateSurat = [];
        if (in_array($surat->status, ['revisi', 'revisi_admin'])) {
            $updateSurat['status'] = 'proses';
        }

        // Simpan alasan keterlambatan jika ada
        if ($request->filled('alasan_keterlambatan')) {
            $updateSurat['alasan_keterlambatan'] = $request->alasan_keterlambatan;
        }

        if (!empty($updateSurat)) {
            $surat->update($updateSurat);
        }

        // Tandai tahap sekarang selesai
        SuratTahapan::where('surat_id', $surat->id)
            ->where('tahap', $surat->tahap_sekarang)
            ->update([
                'status' => 'selesai',
                'diproses_oleh' => Auth::id(),
                'catatan' => $request->catatan,
                'selesai_pada' => now(),
            ]);

        $tahapBerikutnya = $surat->tahap_sekarang + 1;

        if ($tahapBerikutnya > 10) {
            // Surat selesai - setujui_pada dan file_expires_at (3 hari)
            $surat->update([
                'status' => 'selesai',
                'tahap_sekarang' => 10,
                'disetujui_pada' => now(),
                'file_expires_at' => now()->addDays(3),
            ]);

            // Notif ke pengusul: SELESAI
            $surat->user->notify(new SuratStatusNotification(
                surat: $surat,
                type: 'success',
                title: '✅ Surat selesai diproses!',
                message: "Surat \"{$surat->judul}\" telah selesai semua tahapan.",
                url: route('user.surat.show', $surat),
            ));
        } else {
            $updateData = ['tahap_sekarang' => $tahapBerikutnya];

            if ($surat->tahap_sekarang === 5 && $request->filled('nomor_surat')) {
                $updateData['nomor_surat'] = $request->nomor_surat;
                $updateData['tanggal_surat'] = now()->toDateString();
            }

            $surat->update($updateData);
            $surat->refresh();

            SuratTahapan::where('surat_id', $surat->id)
                ->where('tahap', $tahapBerikutnya)
                ->update(['status' => 'proses']);

            // Notif ke pengusul: maju tahap
            $surat->user->notify(new SuratStatusNotification(
                surat: $surat,
                type: 'info',
                title: "📨 Surat maju ke tahap {$tahapBerikutnya}",
                message: "\"{$surat->judul}\" sudah diverifikasi — sekarang: {$surat->nama_tahap}.",
                url: route('user.surat.show', $surat),
            ));

            // Notif ke admin lain: surat diproses
            $this->notifAdminLain($surat, Auth::user(), 'disetujui');
        }

        return redirect()->route('admin.surat.show', $surat)
            ->with('success', 'Surat berhasil disetujui dan maju ke tahap berikutnya.');
    }

    public function tolak(Request $request, Surat $surat)
    {
        $request->validate([
            'catatan' => 'required|string|max:500',
            'jenis_tolak' => 'nullable|string|in:ke_user,ke_admin_aspirasi',
            'alasan_keterlambatan' => ($surat->sla_status === 'terlambat' && !$surat->alasan_keterlambatan) ? 'required|string' : 'nullable',
        ]);

        $statusSebelumnya = $surat->status;
        $jenisTolak = $request->input('jenis_tolak', 'ke_user');

        if ($jenisTolak === 'ke_admin_aspirasi') {
            // Logika: Kembalikan ke Admin Tahap 2

            // 1. Tandai tahap saat ini sebagai 'ditolak'
            SuratTahapan::where('surat_id', $surat->id)
                ->where('tahap', $surat->tahap_sekarang)
                ->update([
                    'status' => 'ditolak',
                    'diproses_oleh' => Auth::id(),
                    'catatan' => $request->catatan,
                    'selesai_pada' => now(),
                ]);

            // 2. Reset Tahap 2 (Admin Aspirasi) agar jadi 'proses' lagi
            SuratTahapan::where('surat_id', $surat->id)
                ->where('tahap', 2)
                ->update([
                    'status' => 'proses',
                    'diproses_oleh' => null,
                    'selesai_pada' => null,
                    // Catatan lama biarkan saja buat histori? Atau hapus? 
                    // Kita timpa saja nanti saat diproses ulang
                ]);

            // 3. Update Surat
            $surat->update([
                'status' => 'revisi_admin',
                'tahap_sekarang' => 2,
                'alasan_keterlambatan' => $request->alasan_keterlambatan ?? $surat->alasan_keterlambatan,
            ]);

            // Notif ke pengusul (User): Surat sedang direvisi internal
            $surat->user->notify(new SuratStatusNotification(
                surat: $surat,
                type: 'warning',
                title: '🔄 Surat sedang direvisi (Internal)',
                message: "Surat \"{$surat->judul}\" sedang dikembalikan ke bagian Aspirasi untuk perbaikan internal. Catatan: {$request->catatan}",
                url: route('user.surat.show', $surat),
            ));

            // Notif ke admin lain (terutama Admin Aspirasi)
            $this->notifAdminLain($surat, Auth::user(), 'revisi_admin');

            return redirect()->route('admin.surat.index')
                ->with('success', 'Surat berhasil dikembalikan ke Admin Aspirasi (Tahap 2).');

        } else {
            // Logika Lama: Tolak ke User
            SuratTahapan::where('surat_id', $surat->id)
                ->where('tahap', $surat->tahap_sekarang)
                ->update([
                    'status' => 'ditolak',
                    'diproses_oleh' => Auth::id(),
                    'catatan' => $request->catatan,
                    'selesai_pada' => now(),
                ]);

            $surat->update([
                'status' => 'ditolak',
                'alasan_keterlambatan' => $request->alasan_keterlambatan ?? $surat->alasan_keterlambatan,
            ]);

            // Notif ke pengusul: DITOLAK
            $surat->user->notify(new SuratStatusNotification(
                surat: $surat,
                type: 'danger',
                title: $statusSebelumnya === 'revisi' ? '❌ File revisi ditolak' : '❌ Surat ditolak',
                message: "Surat \"{$surat->judul}\" " . ($statusSebelumnya === 'revisi' ? 'file revisinya tetap' : 'ditolak') . ". Alasan: {$request->catatan}",
                url: route('user.surat.show', $surat),
            ));

            // Notif ke admin lain
            $this->notifAdminLain($surat, Auth::user(), $statusSebelumnya === 'revisi' ? 'revisi ditolak' : 'ditolak');

            return redirect()->route('admin.surat.index')
                ->with('success', $statusSebelumnya === 'revisi' ? 'File revisi ditolak. User bisa upload ulang.' : 'Surat telah ditolak.');
        }
    }

    // Kirim notif ke semua admin kecuali yang sedang login
    private function notifAdminLain(Surat $surat, $currentUser, string $aksi): void
    {
        User::whereIn('role', ['admin', 'admin_aspirasi', 'admin_kasubbag_tu', 'admin_kepala_balai'])
            ->where('id', '!=', $currentUser->id)
            ->get()
            ->each(function ($admin) use ($surat, $currentUser, $aksi) {
                $admin->notify(new SuratDiprosesNotification(
                    surat: $surat,
                    diprosesByUser: $currentUser,
                    aksi: $aksi,
                ));
            });
    }

    public function preview(Surat $surat, string $tipe)
    {
        if ($surat->file_dihapus_pada) {
            abort(404, 'File sudah tidak tersedia (kadaluarsa)');
        }

        $filePath = $tipe === 'word' ? $surat->file_word : $surat->file_lampiran;

        if (!$filePath || !Storage::disk('private')->exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $fileContent = Storage::disk('private')->get($filePath);
        $fileName = basename($filePath);

        // Jika request minta raw (untuk docx-preview.js)
        if (request()->has('raw')) {
            if (ob_get_level())
                ob_end_clean();

            return response()->file(Storage::disk('private')->path($filePath), [
                'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"'
            ]);
        }

        // PDF
        if ($extension === 'pdf') {
            if (ob_get_level())
                ob_end_clean();
            return response()->file(Storage::disk('private')->path($filePath), [
                'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"'
            ]);
        }

        // Gambar
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'bmp'])) {
            if (ob_get_level())
                ob_end_clean();
            return response()->file(Storage::disk('private')->path($filePath), [
                'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"'
            ]);
        }

        // Jika .doc biasa, paksa download karena docx converter tidak support doc binary
        if ($extension === 'doc') {
            return $this->download($surat, $tipe);
        }

        // Word (.docx) - Tampilkan halaman preview client-side
        if ($extension === 'docx') {
            return response()->view('admin.surat.preview-word', [
                'surat' => $surat,
                'tipe' => $tipe,
                'fileName' => basename($filePath),
            ]);
        }

        // Fallback: download
        return Storage::disk('private')->download($filePath);
    }

    public function download(Surat $surat, string $tipe)
    {
        // Cek apakah file sudah dihapus (expired)
        if ($surat->file_dihapus_pada) {
            abort(404, 'File sudah tidak tersedia (kadaluarsa)');
        }

        $filePath = $tipe === 'word' ? $surat->file_word : $surat->file_lampiran;

        if (!$filePath || !Storage::disk('private')->exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        // Ambil nama file asli
        $originalName = $tipe === 'word' ? $surat->judul : 'lampiran';
        $downloadName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $originalName) . '.' . $extension;

        // MIME types
        $mimeTypes = [
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'webp' => 'image/webp',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'xls' => 'application/vnd.ms-excel',
        ];

        $mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';

        while (ob_get_level()) {
            ob_end_clean();
        }

        return Storage::disk('private')->download($filePath, $downloadName, [
            'Content-Type' => $mimeType,
        ]);
    }
    /**
     * Approve permintaan hapus surat
     */
    public function approveDelete(Request $request, SuratDeleteRequest $deleteRequest)
    {
        // Pastikan request masih pending
        if (!$deleteRequest->isPending()) {
            return back()->with('error', 'Permintaan hapus sudah diproses sebelumnya.');
        }

        $request->validate([
            'admin_catatan' => 'nullable|string|max:500',
        ]);

        // Update status request
        $deleteRequest->update([
            'admin_id' => Auth::id(),
            'status' => 'disetujui',
            'admin_catatan' => $request->admin_catatan,
            'admin_approved_at' => now(),
        ]);

        // Hapus surat
        $surat = $deleteRequest->surat;
        $this->hapusSurat($surat);

        // Notifikasi ke user bahwa surat dihapus
        $surat->user->notify(new SuratStatusNotification(
            surat: $surat,
            type: 'success',
            title: '✅ Permintaan hapus disetujui',
            message: "Surat \"{$surat->judul}\" telah dihapus setelah disetujui admin." . ($request->admin_catatan ? " Catatan: {$request->admin_catatan}" : ''),
            url: route('user.surat.index'),
        ));

        return redirect()->route('admin.surat.index')->with('success', 'Permintaan hapus disetujui. Surat berhasil dihapus.');
    }

    public function rejectDelete(Request $request, SuratDeleteRequest $deleteRequest)
    {
        // Pastikan request masih pending
        if (!$deleteRequest->isPending()) {
            return back()->with('error', 'Permintaan hapus sudah diproses sebelumnya.');
        }

        $request->validate([
            'admin_catatan' => 'required|string|max:500',
        ]);

        $deleteRequest->update([
            'admin_id' => Auth::id(),
            'status' => 'ditolak',
            'admin_catatan' => $request->admin_catatan,
            'admin_approved_at' => now(),
        ]);

        $surat = $deleteRequest->surat;
        $surat->user->notify(new SuratStatusNotification(
            surat: $surat,
            type: 'warning',
            title: '❌ Permintaan hapus ditolak',
            message: "Permintaan hapus surat \"{$surat->judul}\" ditolak. Alasan: {$request->admin_catatan}",
            url: route('user.surat.show', $surat),
        ));

        return back()->with('info', 'Permintaan hapus ditolak.');
    }

    private function hapusSurat(Surat $surat)
    {
        // Hapus file word dari private storage
        if ($surat->file_word) {
            Storage::disk('private')->delete($surat->file_word);
        }

        // Hapus file lampiran dari private storage
        if ($surat->file_lampiran) {
            Storage::disk('private')->delete($surat->file_lampiran);
        }

        $surat->delete();
    }

    public function uploadFileAdmin(Request $request, Surat $surat)
    {
        // Pastikan hanya admin_aspirasi yang bisa update file dan hanya di tahap 2 & 9
        $admin = Auth::user();
        if ($admin->role !== 'admin_aspirasi' || !in_array($surat->tahap_sekarang, [2, 9])) {
            return back()->with('error', 'Akses ditolak. Anda tidak bisa memperbarui file di tahap ini.');
        }

        $request->validate([
            'file_word' => 'nullable|file|mimes:docx,doc,pdf|max:5120',
            'file_lampiran' => 'nullable|file|mimes:pdf,docx,doc,jpg,jpeg,png,xlsx,xls|max:10240',
            'catatan' => 'nullable|string|max:1000',
        ]);

        $updated = false;

        // Handle File Word
        if ($request->hasFile('file_word')) {
            $pathWord = $request->file('file_word')->store('surat/word', 'private');
            if ($surat->file_word) {
                Storage::disk('private')->delete($surat->file_word);
            }
            $surat->file_word = $pathWord;
            $updated = true;
        }

        // Handle File Lampiran
        if ($request->hasFile('file_lampiran')) {
            $pathLampiran = $request->file('file_lampiran')->store('surat/lampiran', 'private');
            if ($surat->file_lampiran) {
                Storage::disk('private')->delete($surat->file_lampiran);
            }
            $surat->file_lampiran = $pathLampiran;
            $updated = true;
        }

        if ($updated) {
            $surat->save();

            // Berikan notifikasi ke user jika file diubah admin (biasanya tahap 2)
            if ($surat->user) {
                $surat->user->notify(new SuratStatusNotification(
                    surat: $surat,
                    type: 'info',
                    title: '📝 File Surat Diperbarui Admin',
                    message: "Admin Aspirasi telah melakukan penyesuaian/perbaikan format pada file surat \"{$surat->judul}\".",
                    url: route('user.surat.show', $surat)
                ));
            }

            return back()->with('success', 'File surat berhasil diperbarui.');
        }

        return back()->with('error', 'Tidak ada file yang diunggah.');
    }
}