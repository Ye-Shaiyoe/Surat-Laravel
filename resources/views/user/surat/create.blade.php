@extends('layouts.user')
@section('title', 'Ajukan Surat')

@section('content')

<div class="row justify-content-center">
    <div class="col-12 col-lg-8">

        {{-- Header --}}
        <div class="d-flex align-items-center gap-2 mb-4">
            <a href="{{ route('dashboard') }}" class="btn btn-sm btn-light" style="border-radius:8px;">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h5 class="fw-bold mb-0" style="color:#1e3a5f;">📝 Pengajuan Surat Baru</h5>
                <small class="text-muted">Isi form berikut dengan lengkap dan benar</small>
            </div>
        </div>

        <div class="card card-custom">
            <div class="card-body p-4">
                <form action="{{ route('user.surat.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- STEP 1: Info Surat --}}
                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div style="width:24px;height:24px;border-radius:50%;background:#1e3a5f;color:#fff;font-size:11px;font-weight:700;display:flex;align-items:center;justify-content:center;">1</div>
                            <span class="fw-semibold" style="font-size:14px;color:#1e3a5f;">Informasi Surat</span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" style="font-size:13px;font-weight:500;">
                                Judul Surat <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="judul" value="{{ old('judul') }}"
                                   class="form-control @error('judul') is-invalid @enderror"
                                   placeholder="Contoh: Permohonan Kalibrasi Alat Ukur Timbangan"
                                   style="font-size:13px; border-radius:8px;">
                            @error('judul')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" style="font-size:13px;font-weight:500;">
                                    Jenis Surat <span class="text-danger">*</span>
                                </label>
                                <select name="jenis" class="form-select @error('jenis') is-invalid @enderror"
                                        style="font-size:13px; border-radius:8px;">
                                    <option value="">-- Pilih Jenis --</option>
                                    @foreach(\App\Models\Surat::JENIS_LABEL as $val => $label)
                                        <option value="{{ $val }}" {{ old('jenis') === $val ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('jenis')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" style="font-size:13px;font-weight:500;">
                                    Sifat Surat <span class="text-danger">*</span>
                                </label>
                                <select name="sifat" class="form-select @error('sifat') is-invalid @enderror"
                                        style="font-size:13px; border-radius:8px;">
                                    <option value="biasa"   {{ old('sifat','biasa') === 'biasa'   ? 'selected' : '' }}>Biasa</option>
                                    <option value="segera"  {{ old('sifat') === 'segera'  ? 'selected' : '' }}>Segera</option>
                                    <option value="rahasia" {{ old('sifat') === 'rahasia' ? 'selected' : '' }}>Rahasia</option>
                                </select>
                                @error('sifat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="form-label" style="font-size:13px;font-weight:500;">
                                Tujuan Surat <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="tujuan" value="{{ old('tujuan') }}"
                                   class="form-control @error('tujuan') is-invalid @enderror"
                                   placeholder="Contoh: Kepala Dinas Perdagangan Provinsi Jawa Barat"
                                   style="font-size:13px; border-radius:8px;">
                            @error('tujuan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr style="border-color:#f1f3f5;">

                    {{-- STEP 2: Upload --}}
                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div style="width:24px;height:24px;border-radius:50%;background:#1e3a5f;color:#fff;font-size:11px;font-weight:700;display:flex;align-items:center;justify-content:center;">2</div>
                            <span class="fw-semibold" style="font-size:14px;color:#1e3a5f;">Upload Dokumen</span>
                        </div>

                        {{-- Template hint --}}
                        @if($templates->isNotEmpty())
                            <div class="alert alert-info py-2 px-3 mb-3" style="font-size:12px; border-radius:8px; border:none; background:#eff6ff; color:#1d4ed8;">
                                <i class="bi bi-info-circle me-1"></i>
                                Belum punya template? Download dulu:
                                @foreach($templates as $tpl)
                                    <a href="{{ $tpl['url'] }}" target="_blank"
                                       class="fw-semibold text-decoration-none ms-1">{{ $tpl['nama'] }}</a>
                                @endforeach
                            </div>
                        @endif

                        {{-- Upload file word --}}
                        <div class="mb-3">
                            <label class="form-label" style="font-size:13px;font-weight:500;">
                                File Surat (.docx) <span class="text-danger">*</span>
                            </label>
                            <label class="upload-area d-block" for="file_word">
                                <input type="file" id="file_word" name="file_word"
                                       accept=".docx,.doc"
                                       class="@error('file_word') is-invalid @enderror"
                                       onchange="showFileName(this, 'nama_word')">
                                <i class="bi bi-file-earmark-word" style="font-size:28px; color:#2563eb; display:block; margin-bottom:6px;"></i>
                                <span id="nama_word" style="font-size:12px;">
                                    Klik atau drag file .docx ke sini<br>
                                    <span style="font-size:11px; color:#94a3b8;">Maks. 10MB</span>
                                </span>
                            </label>
                            @error('file_word')
                                <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Upload lampiran --}}
                        <div>
                            <label class="form-label" style="font-size:13px;font-weight:500;">
                                Lampiran (opsional)
                                <span class="text-muted fw-normal" style="font-size:11px;">PDF, JPG, PNG</span>
                            </label>
                            <label class="upload-area d-block" for="file_lampiran">
                                <input type="file" id="file_lampiran" name="file_lampiran"
                                       accept=".pdf,.jpg,.jpeg,.png"
                                       class="@error('file_lampiran') is-invalid @enderror"
                                       onchange="showFileName(this, 'nama_lampiran')">
                                <i class="bi bi-paperclip" style="font-size:24px; display:block; margin-bottom:6px;"></i>
                                <span id="nama_lampiran" style="font-size:12px;">
                                    Klik untuk upload lampiran<br>
                                    <span style="font-size:11px; color:#94a3b8;">Maks. 20MB</span>
                                </span>
                            </label>
                            @error('file_lampiran')
                                <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr style="border-color:#f1f3f5;">

                    {{-- INFO SLA --}}
                    <div class="alert py-2 px-3 mb-4" style="background:#eff6ff;border:none;border-radius:8px;">
                        <div style="font-size:12px; color:#1d4ed8;">
                            <i class="bi bi-clock me-1"></i>
                            <strong>SLA 1 Hari Kerja</strong> — surat akan diproses maksimal 1 hari kerja setelah diajukan.
                        </div>
                    </div>

                    {{-- SUBMIT --}}
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('dashboard') }}" class="btn btn-light" style="border-radius:8px; font-size:13px;">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary d-flex align-items-center gap-2"
                                style="background:#1e3a5f; border-color:#1e3a5f; border-radius:8px; font-size:13px; font-weight:600;">
                            <i class="bi bi-send-fill"></i> Submit Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
function showFileName(input, targetId) {
    const el = document.getElementById(targetId);
    if (input.files && input.files[0]) {
        const name = input.files[0].name;
        const size = (input.files[0].size / 1024).toFixed(0);
        el.innerHTML = `<strong style="color:#1e3a5f;">${name}</strong><br><span style="font-size:11px;color:#6b7280;">${size} KB · Siap diupload</span>`;
        input.closest('.upload-area').style.borderColor = '#22c55e';
        input.closest('.upload-area').style.background = '#f0fdf4';
    }
}
</script>
@endpush

@endsection