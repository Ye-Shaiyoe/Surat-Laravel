@extends('layouts.app')

@section('title', 'Pengajuan Surat')

@section('content')

<div class="card">
    <div class="card-head">📨 Form Pengajuan Surat</div>

    <div class="card-body">
        <form action="{{ route('persuratan.pengajuan.kirim') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Judul -->
            <div class="form-group">
                <label>Judul Surat</label>
                <input type="text" name="judul" class="form-control" required>
            </div>

            <!-- Tujuan -->
            <div class="form-group">
                <label>Tujuan Surat</label>
                <input type="text" name="tujuan" class="form-control" required>
            </div>

            <!-- Sifat -->
            <div class="form-group">
                <label>Sifat Surat</label>
                <select name="sifat" class="form-control" required>
                    <option value="">-- Pilih --</option>
                    <option value="biasa">Biasa</option>
                    <option value="segera">Segera</option>
                    <option value="rahasia">Rahasia</option>
                </select>
            </div>

            <!-- Upload File Word -->
            <div class="form-group">
                <label>Upload File Surat (Word)</label>
                <input type="file" name="file_surat" class="form-control" accept=".doc,.docx" required>
            </div>

            <!-- Upload Lampiran -->
            <div class="form-group">
                <label>Upload Lampiran</label>
                <input type="file" name="lampiran" class="form-control">
            </div>

            <!-- Submit -->
            <div style="margin-top:15px">
                <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
            </div>
        </form>
    </div>
</div>

@endsection