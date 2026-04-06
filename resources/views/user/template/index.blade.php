@extends('layouts.user')

@section('content')

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="{{ route('dashboard') }}" class="btn btn-sm btn-light" style="border-radius:8px;">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div>
        <h5 class="fw-bold mb-0" style="color:#1e3a5f;">📄 Template Surat</h5>
        <small class="text-muted">Unduh template untuk mengajukan surat</small>
    </div>
</div>

<div class="card card-custom">
    <div class="card-body px-4 py-4">
        @forelse($templates as $tpl)
            <div class="d-flex align-items-center gap-3 py-2 border-bottom">
                <i class="bi bi-file-earmark-word" style="color:#2563eb; font-size:22px;"></i>
                <span style="font-size:14px; flex:1; color:#374151;">{{ $tpl['nama'] }}</span>
                <a href="{{ $tpl['url'] }}" target="_blank" rel="noopener noreferrer"
                   class="btn btn-sm btn-primary" style="font-size:12px; border-radius:7px; background:#1e3a5f; border-color:#1e3a5f;">
                    <i class="bi bi-download me-1"></i> Unduh
                </a>
            </div>
        @empty
            <p class="text-muted mb-0" style="font-size:14px;">Belum ada template yang diunggah admin.</p>
        @endforelse
    </div>
</div>

@endsection
