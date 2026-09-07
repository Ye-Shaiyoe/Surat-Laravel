<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Surat;
use App\Models\SuratDeleteRequest;
use App\Models\User;
use Carbon\Carbon;
use App\Notifications\SuratMasukNotification;
use App\Notifications\DeleteRequestNotification;
use App\Notifications\SuratDeletedNotification;
use App\Notifications\FileRevisiNotification;
use App\Notifications\SuratPurgedNotification;
use App\Http\Requests\StoreSuratRequest;
use App\Http\Requests\UpdateSuratRequest;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UserSuratExport;

class SuratController extends Controller
{
    public function index(Request $request)
    {
        $query = Surat::where('user_id', Auth::id())
            ->with('tahapans')
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', '!=', 'draft');
        }

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }
        if ($request->filled('tahun')) {
            $query->whereYear('created_at', $request->tahun);
        }
        if ($request->filled('bulan')) {
            $query->whereMonth('created_at', $request->bulan);
        }

        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        $surats = $query->paginate(10)->withQueryString();
        $title = $request->status === 'draft' ? 'Draft Surat Saya' : 'Surat Saya';

        return view('user.surat.index', compact('surats', 'title'));
    }

    public function fileIndex(Request $request)
    {
        $query = Surat::where('user_id', Auth::id())
            ->where('status', 'selesai')
            ->where(function ($q) {
                $q->whereNotNull('file_word')
                    ->orWhereNotNull('file_lampiran');
            })
            ->latest();

        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        $surats = $query->paginate(15)->withQueryString();
        $title = 'Manajemen File Fisik Surat';

        return view('user.surat.file_index', compact('surats', 'title'));
    }

    public function table(Request $request)
    {
        $query = $this->buildUserTableQuery($request);

        $surats = $query->paginate(15)->withQueryString();
        $title = $request->status === 'draft' ? 'Tabel Draft Surat' : 'Tabel Surat Saya';

        return view('user.surat.table', compact('surats', 'title'));
    }

    public function exportExcel(Request $request)
    {
        $filters = $request->only([
            'status', 'jenis', 'tahun', 'bulan', 'search',
            'tanggal_mulai', 'tanggal_selesai', 'start_date', 'end_date'
        ]);

        $tglMulai = $filters['tanggal_mulai'] ?? $filters['start_date'] ?? null;
        $tglSelesai = $filters['tanggal_selesai'] ?? $filters['end_date'] ?? null;

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

        $sanitizedName = preg_replace('/[^A-Za-z0-9_\-]/', '_', Auth::user()->name);
        $fileName = 'Data_Surat_' . $sanitizedName . $dateSuffix . '.xlsx';

        return Excel::download(new UserSuratExport($filters), $fileName);
    }

    /**
     * Build query filter untuk tabel dan export surat pengguna
     */
    protected function buildUserTableQuery(Request $request)
    {
        $query = Surat::where('user_id', Auth::id())
            ->with('tahapans')
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', '!=', 'draft');
        }

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        // Filter Rentang Tanggal Pengajuan (created_at)
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
            // Gunakan filter tahun dan bulan jika rentang tanggal tidak diisi
            if ($request->filled('tahun')) {
                $query->whereYear('created_at', $request->tahun);
            }
            if ($request->filled('bulan')) {
                $query->whereMonth('created_at', $request->bulan);
            }
        }

        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        return $query;
    }

    public function create()
    {
        $isLibur = $this->isLayananTutup();
        $templates = collect(Storage::disk('private')->files('templates'))
            ->map(fn($path) => [
                'nama' => basename($path),
                'url' => route('user.template.download', ['nama' => basename($path)]),
            ])->values();

        return view('user.surat.create', compact('templates', 'isLibur'));
    }

    public function store(StoreSuratRequest $request)
    {
        $isDraft = $request->isDraft();

        if ($this->isLayananTutup() && !$isDraft) {
            return back()->with('error', 'Mohon maaf, pengajuan surat baru hanya tersedia pada hari kerja. Senin–Kamis pukul 07.00–16.00 WIB, Jumat pukul 07.30–16.30 WIB. Sabtu & Minggu libur. Namun Anda tetap bisa menyimpan sebagai draf.');
        }

        // Upload file ke disk 'private' (validated & sanitized oleh StoreSuratRequest)
        $fileWord = $request->hasFile('file_word')
            ? $request->file('file_word')->store('surat/word', 'private')
            : null;
        $fileLamp = $request->hasFile('file_lampiran')
            ? $request->file('file_lampiran')->store('surat/lampiran', 'private')
            : null;

        // Hitung deadline SLA jika bukan draft
        $deadline = !$isDraft ? $this->hitungSLA(now()) : null;

        $surat = Surat::create([
            'user_id' => Auth::id(),
            'judul' => $request->judul ?? 'Draft Surat ' . now()->format('d/m/Y H:i'),
            'jenis' => $request->jenis ?? 'nota_dinas', // Default for drafts
            'sifat' => $request->sifat ?? 'biasa',
            'tujuan' => $request->tujuan ?? '', // Default for drafts
            'catatan_pengusul' => $request->catatan_pengusul,
            'file_word' => $fileWord,
            'file_lampiran' => $fileLamp,
            'tahap_sekarang' => 1,
            'status' => $isDraft ? 'draft' : 'proses',
            'deadline_sla' => $deadline,
        ]);

        if (!$isDraft) {
            // Inisialisasi tahapan hanya jika submit beneran
            $surat->initTahapan();
            $surat->tahapans()->where('tahap', 2)->update(['status' => 'proses']);
            $surat->update(['tahap_sekarang' => 2]);

            // Notif admin
            User::whereIn('role', ['admin', 'admin_aspirasi', 'admin_kasubbag_tu', 'admin_kepala_balai'])
                ->each(fn($a) => $a->notify(new SuratMasukNotification($surat)));

            return redirect()->route('user.surat.show', $surat)
                ->with('success', 'Surat berhasil diajukan!');
        }

        return redirect()->route('user.surat.index')
            ->with('success', 'Draft surat berhasil disimpan.');
    }

    public function edit(Surat $surat)
    {
        // Pastikan hanya pemilik yang bisa edit draft
        abort_if($surat->user_id !== Auth::id() || $surat->status !== 'draft', 403);

        $templates = collect(Storage::disk('private')->files('templates'))
            ->map(fn($path) => [
                'nama' => basename($path),
                'url' => route('user.template.download', ['nama' => basename($path)]),
            ])->values();

        $isLibur = $this->isLayananTutup();
        return view('user.surat.edit', compact('surat', 'templates', 'isLibur'));
    }

    public function update(UpdateSuratRequest $request, Surat $surat)
    {
        // Otorisasi sudah ditangani oleh UpdateSuratRequest::authorize()
        $isDraft = $request->isDraft();

        if ($this->isLayananTutup() && !$isDraft) {
            return back()->with('error', 'Mohon maaf, pengajuan surat baru hanya tersedia pada hari kerja. Senin–Kamis pukul 07.00–16.00 WIB, Jumat pukul 07.30–16.30 WIB. Sabtu & Minggu libur. Namun Anda tetap bisa menyimpan draf ini.');
        }

        if ($request->hasFile('file_word')) {
            if ($surat->file_word)
                Storage::disk('private')->delete($surat->file_word);
            $surat->file_word = $request->file('file_word')->store('surat/word', 'private');
        }

        if ($request->hasFile('file_lampiran')) {
            if ($surat->file_lampiran)
                Storage::disk('private')->delete($surat->file_lampiran);
            $surat->file_lampiran = $request->file('file_lampiran')->store('surat/lampiran', 'private');
        }

        $surat->judul = $request->judul ?? $surat->judul;
        $surat->jenis = $request->jenis ?? $surat->jenis;
        $surat->sifat = $request->sifat ?? $surat->sifat;
        $surat->tujuan = $request->tujuan ?? $surat->tujuan;
        if ($request->has('catatan_pengusul')) {
            $surat->catatan_pengusul = $request->catatan_pengusul;
        }

        if (!$isDraft) {
            $surat->status = 'proses';
            $surat->deadline_sla = $this->hitungSLA(now());
            $surat->save();

            $surat->initTahapan();
            $surat->tahapans()->where('tahap', 2)->update(['status' => 'proses']);
            $surat->update(['tahap_sekarang' => 2]);

            User::whereIn('role', ['admin', 'admin_aspirasi', 'admin_kasubbag_tu', 'admin_kepala_balai'])
                ->each(fn($a) => $a->notify(new SuratMasukNotification($surat)));

            return redirect()->route('user.surat.show', $surat)
                ->with('success', 'Draft berhasil diajukan!');
        }

        $surat->save();
        return redirect()->route('user.surat.index')->with('success', 'Draft berhasil diperbarui.');
    }

    public function updateMetadata(Request $request, Surat $surat)
    {
        abort_if($surat->user_id !== Auth::id(), 403);

        $isDalamWaktu = $surat->created_at && $surat->created_at->diffInMinutes(now()) <= 15;
        if (!$isDalamWaktu) {
            return back()->with('error', 'Waktu edit telah habis. Surat hanya bisa diedit dalam 15 menit pertama setelah diajukan.');
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'jenis' => 'required|in:nota_dinas,surat_dinas,surat_keputusan,surat_pernyataan,surat_keterangan,surat_undangan,surat_lainnya',
            'sifat' => 'required|in:biasa,segera,rahasia',
            'tujuan' => 'required|string|max:500',
            'catatan_pengusul' => 'nullable|string|max:100',
        ]);

        $surat->update([
            'judul' => strip_tags(trim($request->judul)),
            'jenis' => $request->jenis,
            'sifat' => $request->sifat,
            'tujuan' => strip_tags(trim($request->tujuan)),
            'catatan_pengusul' => $request->catatan_pengusul ? strip_tags(trim($request->catatan_pengusul)) : null,
        ]);

        return back()->with('success', 'Detail surat berhasil diperbarui.');
    }

    public function show($surat)
    {
        // Cari berdasarkan UUID dulu (standar baru)
        $suratModel = Surat::where('uuid', $surat)
            ->where('user_id', Auth::id())
            ->first();

        // Fallback: Jika tidak ketemu dan inputnya angka, coba cari berdasarkan ID (untuk link lama)
        if (!$suratModel && is_numeric($surat)) {
            $suratModel = Surat::where('id', $surat)
                ->where('user_id', Auth::id())
                ->first();

            if ($suratModel) {
                // Redirect otomatis ke URL versi UUID biar rapi
                return redirect()->route('user.surat.show', $suratModel);
            }
        }

        if (!$suratModel) {
            abort(404, 'Surat tidak ditemukan atau Anda tidak memiliki akses.');
        }

        $suratModel->load([
            'tahapans' => function ($query) {
                $query->orderBy('tahap')->with('diprosesByUser');
            }
        ]);

        return view('user.surat.show', ['surat' => $suratModel]);
    }

    // Hitung SLA: 30 jam kalender, melewati weekend (Sabtu/Minggu tidak dihitung)
    // Hasil: deadline = waktu_submit + 30 jam, hari Sabtu/Minggu dilewati
    private function hitungSLA(Carbon $dari): Carbon
    {
        $sla = $dari->copy();

        // Tambahkan 30 jam, skip hari Sabtu & Minggu
        $hoursToAdd = 30;
        while ($hoursToAdd > 0) {
            $sla->addHour();
            if (!$sla->isWeekend()) {
                $hoursToAdd--;
            }
        }

        return $sla;
    }

    /**
     * Request delete surat (butuh approval admin jika sedang diproses)
     */
    public function requestDelete(Request $request, Surat $surat)
    {
        // Pastikan hanya pemilik yang bisa request
        abort_if($surat->user_id !== Auth::id(), 403);

        // Cek apakah sudah ada request delete yang pending
        $existingRequest = SuratDeleteRequest::where('surat_id', $surat->id)
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            return back()->with('error', 'Permintaan hapus sudah pernah dikirim dan masih menunggu approval admin.');
        }

        // Bisa langsung hapus tanpa persetujuan admin jika:
        // - Surat masih draft (belum diajukan)
        // - Surat sudah selesai atau ditolak
        // - Surat sedang di proses tapi BARU sampai tahap 1-2 (Usulan / Verifikasi Arsiparis)
        $bisaLangsungHapus = in_array($surat->status, ['draft', 'ditolak', 'selesai'])
            || ($surat->status === 'proses' && $surat->tahap_sekarang <= 2);

        if ($bisaLangsungHapus) {
            // Validasi ringan, alasan opsional
            $request->validate([
                'alasan' => 'nullable|string|max:500',
            ]);

            // Langsung hapus surat
            $this->hapusSurat($surat);

            // Notifikasi ke semua admin (sekadar info)
            $alasan = $request->alasan ?? 'Penghapusan manual oleh user';
            User::whereIn('role', ['admin', 'admin_aspirasi', 'admin_kasubbag_tu', 'admin_kepala_balai'])
                ->each(function ($admin) use ($surat, $alasan) {
                    $admin->notify(new SuratDeletedNotification($surat, $alasan));
                });

            return redirect()->route('user.surat.index')
                ->with('success', 'Surat berhasil dihapus.');
        }

        // Jika sedang diproses - buat request delete dengan status pending
        $request->validate([
            'alasan' => 'required|string|max:500',
        ]);

        $deleteRequest = SuratDeleteRequest::create([
            'surat_id' => $surat->id,
            'user_id' => Auth::id(),
            'alasan' => $request->alasan,
            'status' => 'pending',
        ]);

        // Kirim notifikasi ke semua admin
        User::whereIn('role', ['admin', 'admin_aspirasi', 'admin_kasubbag_tu', 'admin_kepala_balai'])
            ->each(function ($admin) use ($deleteRequest) {
                $admin->notify(new DeleteRequestNotification($deleteRequest));
            });

        return back()->with('info', 'Permintaan hapus telah dikirim. Menunggu persetujuan admin.');
    }

    /**
     * Hapus surat (setelah disetujui admin atau bisa langsung hapus)
     */
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

        // Hapus surat (tahapans akan terhapus otomatis karena cascade)
        $surat->delete();
    }

    /**
     * Upload ulang file perbaikan jika surat ditolak
     */
    public function reuploadFile(Request $request, Surat $surat)
    {
        // Pastikan hanya pemilik yang bisa upload ulang
        abort_if($surat->user_id !== Auth::id(), 403);

        // Hanya bisa jika status ditolak
        if (!$surat->bisaRevisi()) {
            return back()->with('error', 'Upload ulang hanya bisa dilakukan jika surat ditolak.');
        }

        $request->validate([
            'file_word' => 'required|file|mimes:docx,doc,pdf|max:5120',
            'file_lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png,docx,doc,xlsx,xls|max:10240',
        ]);

        if ($surat->file_word) {
            Storage::disk('private')->delete($surat->file_word);
        }
        if ($surat->file_lampiran) {
            Storage::disk('private')->delete($surat->file_lampiran);
        }

        // Upload file baru ke private disk
        $fileWord = $request->file('file_word')->store('surat/word', 'private');
        $fileLamp = $request->file('file_lampiran')
            ? $request->file('file_lampiran')->store('surat/lampiran', 'private')
            : null;

        $surat->update([
            'file_word' => $fileWord,
            'file_lampiran' => $fileLamp,
            'status' => 'revisi',
            'tahap_sekarang' => 2,
            'status_revisi' => true,
            'revisi_count' => $surat->revisi_count + 1,
            'revisi_uploaded_at' => now(),
        ]);

        // Reset tahap 1 ke status selesai (sudah lewat)
        $surat->tahapans()->where('tahap', 1)->update([
            'status' => 'selesai',
        ]);

        // Tahap 2 sedang di-verifikasi ulang - RESET TOTAL
        $surat->tahapans()->where('tahap', 2)->update([
            'status' => 'proses',
            'selesai_pada' => null,
            'diproses_oleh' => null,
            'catatan' => null,
        ]);

        // Reset tahapan setelah tahap 2 menjadi 'menunggu' karena akan diverifikasi ulang
        $surat->tahapans()->where('tahap', '>', 2)->update([
            'status' => 'menunggu',
            'selesai_pada' => null,
            'diproses_oleh' => null,
            'catatan' => null,
        ]);

        // Notif ke SEMUA admin
        User::whereIn('role', ['admin', 'admin_aspirasi', 'admin_kasubbag_tu', 'admin_kepala_balai'])
            ->each(fn($a) => $a->notify(new FileRevisiNotification(
                $surat,
                2,
                'Verifikasi Arsiparis'
            )));

        return back()->with('success', 'File perbaikan berhasil diupload! Menunggu review admin.');
    }

    /**
     * Download template surat untuk user
     */
    public function templateDownload(string $nama)
    {
        $safeName = basename($nama);
        $path = 'templates/' . $safeName;
        if (!Storage::disk('private')->exists($path)) {
            abort(404, 'Template tidak ditemukan');
        }

        $extension = strtolower(pathinfo($safeName, PATHINFO_EXTENSION));
        $mimeTypes = [
            'pdf'  => 'application/pdf',
            'doc'  => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];
        $mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';

        while (ob_get_level()) {
            ob_end_clean();
        }

        return Storage::disk('private')->download($path, $safeName, [
            'Content-Type' => $mimeType,
        ]);
    }

    /**
     * Preview file untuk user (PDF/Image inline, Word via converter)
     */
    public function preview(Surat $surat, string $tipe)
    {
        // Pastikan hanya pemilik yang bisa melihat
        abort_if($surat->user_id !== Auth::id(), 403);

        $filePath = $tipe === 'word' ? $surat->file_word : $surat->file_lampiran;

        if (!$filePath) {
            Log::warning('User attempting to preview non-existent file path', ['surat_id' => $surat->id, 'tipe' => $tipe]);
            abort(404, 'File tidak ditemukan');
        }

        $fullPath = Storage::disk('private')->path($filePath);
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        Log::info('Previewing file', [
            'surat_id' => $surat->id,
            'tipe' => $tipe,
            'filePath' => $filePath,
            'fullPath' => $fullPath,
            'extension' => $extension,
            'fileExists' => Storage::disk('private')->exists($filePath)
        ]);

        if (!Storage::disk('private')->exists($filePath)) {
            Log::error('File not found on disk for preview', ['fullPath' => $fullPath, 'filePath' => $filePath]);

            // Jika file hilang tapi database masih punya reference, clear reference
            if ($tipe === 'word') {
                $surat->update(['file_word' => null]);
            } else {
                $surat->update(['file_lampiran' => null]);
            }

            return back()->with('error', 'File tidak ditemukan. File mungkin sudah dihapus atau berhasil di-update dengan versi baru.');
        }

        // Cek jika request minta raw file (untuk iframe/img source)
        if (request()->has('raw')) {
            if (!Storage::disk('private')->exists($filePath)) {
                abort(404, 'File tidak ditemukan di disk.');
            }

            while (ob_get_level()) {
                ob_end_clean();
            }

            return response()->file($fullPath, [
                'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"'
            ]);
        }

        // PDF - return file directly (seperti admin)
        if ($extension === 'pdf') {
            if (ob_get_level())
                ob_end_clean();
            return response()->file($fullPath, [
                'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"'
            ]);
        }

        // Image - return file directly (seperti admin)
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'bmp'])) {
            if (ob_get_level())
                ob_end_clean();
            return response()->file($fullPath, [
                'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"'
            ]);
        }

        // Jika file tidak bisa di-preview di browser (seperti excel, zip, rar, doc), paksa download
        if (in_array($extension, ['doc', 'xls', 'xlsx', 'csv', 'zip', 'rar'])) {
            return $this->download($surat, $tipe);
        }

        // Word (convert ke HTML)
        if ($extension === 'docx') {
            $converter = new \App\Services\DocxToHtmlConverter($fullPath);
            $htmlRaw = $converter->convert();

            // Sanitasi HTML sebelum ditampilkan ke browser
            $htmlContent = \App\Services\HtmlSanitizer::clean($htmlRaw);

            return view('user.surat.preview', [
                'surat' => $surat,
                'htmlContent' => $htmlContent,
                'tipe' => $tipe,
                'fileName' => basename($filePath),
            ]);
        }

        return $this->download($surat, $tipe);
    }

    /**
     * Download file untuk user
     */
    public function download(Surat $surat, string $tipe)
    {
        // Pastikan hanya pemilik yang bisa mendownload
        abort_if($surat->user_id !== Auth::id(), 403);

        $filePath = $tipe === 'word' ? $surat->file_word : $surat->file_lampiran;

        if (!$filePath || !Storage::disk('private')->exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $originalName = $tipe === 'word' ? $surat->judul : 'lampiran';
        $downloadName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $originalName) . '.' . $extension;

        $mimeTypes = [
            'pdf'  => 'application/pdf',
            'doc'  => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png'  => 'image/png',
            'webp' => 'image/webp',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'xls'  => 'application/vnd.ms-excel',
            'csv'  => 'text/csv',
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
     * Get MIME type from file extension.
     */
    private function getMimeTypeFromExtension(string $extension): string
    {
        return match (strtolower($extension)) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'bmp' => 'image/bmp',
            'webp' => 'image/webp',
            default => 'application/octet-stream',
        };
    }

    /**
     * Hapus hanya file fisik (Word & Lampiran) tapi tetap simpan data suratnya.
     * Khusus untuk surat yang sudah SELESAI.
     */
    public function purgeFiles(Surat $surat)
    {
        // Pastikan hanya pemilik
        abort_if($surat->user_id !== Auth::id(), 403);

        // Hanya untuk surat yang sudah selesai
        if ($surat->status !== 'selesai') {
            return back()->with('error', 'Pembersihan file hanya bisa dilakukan untuk surat yang sudah selesai pemrosesannya.');
        }

        // Hapus file fisik dari private storage
        if ($surat->file_word) {
            Storage::disk('private')->delete($surat->file_word);
        }
        if ($surat->file_lampiran) {
            Storage::disk('private')->delete($surat->file_lampiran);
        }

        // Update database (kosongkan path file tapi tandai dihapus)
        $surat->update([
            'file_word' => null,
            'file_lampiran' => null,
            'file_dihapus_pada' => now(),
        ]);

        // Notifikasi ke semua admin
        User::whereIn('role', ['admin', 'admin_aspirasi', 'admin_kasubbag_tu', 'admin_kepala_balai'])
            ->each(function ($admin) use ($surat) {
                $admin->notify(new SuratPurgedNotification($surat, Auth::user()->name));
            });

        return back()->with('success', 'File fisik surat berhasil dibersihkan dari penyimpanan. Tracking tetap tersimpan.');
    }

    /**
     * Berikan rating untuk surat yang sudah selesai
     */
    public function rate(Request $request, Surat $surat)
    {
        abort_if($surat->user_id !== Auth::id(), 403);
        
        if ($surat->status !== 'selesai') {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Rating hanya bisa diberikan untuk surat yang sudah selesai.'], 422);
            }
            return back()->with('error', 'Rating hanya bisa diberikan untuk surat yang sudah selesai.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $surat->update([
            'rating' => $request->rating,
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Terima kasih atas penilaian Anda!']);
        }

        return back()->with('success', 'Terima kasih atas penilaian Anda!');
    }

    /**
     * Cek apakah layanan sedang tutup.
     * Senin–Kamis: 07:30 – 16:00 WIB
     * Jumat       : 07:30 – 16:30 WIB
     * Sabtu–Minggu: Libur
     */
    private function isLayananTutup(): bool
    {
        $now = now();
        if ($now->isWeekend())
            return true;

        $dayOfWeek = $now->dayOfWeek; // 1 (Mon) - 7 (Sun)
        $timeInMinutes = $now->hour * 60 + $now->minute;

        // Senin–Kamis: 07:30 – 16:00
        if ($dayOfWeek >= 1 && $dayOfWeek <= 4) {
            $start = 7 * 60 + 30; // 07:30
            $end = 16 * 60; // 16:00
            return $timeInMinutes < $start || $timeInMinutes >= $end;
        }

        // Jumat: 07:30 – 16:30
        if ($dayOfWeek === 5) {
            $start = 7 * 60 + 30; // 07:30
            $end = 16 * 60 + 30; // 16:30
            return $timeInMinutes < $start || $timeInMinutes >= $end;
        }

        return true;
    }
}