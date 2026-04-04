<<<<<<< HEAD
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
=======
@extends('layouts.app')
@section('title','Dashboard')
@section('subtitle','Layanan Ketatausahaan — Direktorat Metrologi')

@section('content')

<div class="stats">
  <div class="stat"><div class="stat-icon">📨</div><div><div class="stat-num">{{ $totalSurat ?? 0 }}</div><div class="stat-label">Total Surat</div></div></div>
  <div class="stat"><div class="stat-icon">🔄</div><div><div class="stat-num">{{ $proses ?? 0 }}</div><div class="stat-label">Dalam Proses</div></div></div>
  <div class="stat"><div class="stat-icon">✅</div><div><div class="stat-num">{{ $selesai ?? 0 }}</div><div class="stat-label">Selesai</div></div></div>
  <div class="stat"><div class="stat-icon">⚠️</div><div><div class="stat-num">{{ $slaLambat ?? 0 }}</div><div class="stat-label">SLA Terlambat</div></div></div>
</div>

<div class="card">
  <div class="card-head">🏛️ Menu Layanan</div>
  <div class="card-body">
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px">
      @php
      $menu = [
        ['icon'=>'✉️','nama'=>'Persuratan','desc'=>'Format, pengajuan & laporan surat','url'=>route('persuratan.pengajuan'),'warna'=>'#dbeafe'],
        ['icon'=>'📝','nama'=>'Layanan Ketatausahaan','desc'=>'Pengajuan layanan ketatausahaan','url'=>'#','warna'=>'#dcfce7'],
        ['icon'=>'🏗️','nama'=>'Sarana & Prasarana','desc'=>'Laporan sarana dan prasarana','url'=>'#','warna'=>'#fef3c7'],
        ['icon'=>'📦','nama'=>'Persediaan','desc'=>'Kelola data persediaan','url'=>'#','warna'=>'#ede9fe'],
        ['icon'=>'📋','nama'=>'Agenda','desc'=>'Agenda kegiatan kantor','url'=>'#','warna'=>'#fce7f3'],
        ['icon'=>'📊','nama'=>'Rekap Data','desc'=>'Rekap data ketatausahaan','url'=>'#','warna'=>'#f0fdf4'],
      ];
      @endphp
      @foreach($menu as $m)
      <a href="{{ $m['url'] }}" style="text-decoration:none">
        <div style="background:{{ $m['warna'] }};border-radius:9px;padding:16px;cursor:pointer">
          <div style="font-size:1.6rem;margin-bottom:8px">{{ $m['icon'] }}</div>
          <div style="font-size:0.85rem;font-weight:700;color:#0f172a;margin-bottom:3px">{{ $m['nama'] }}</div>
          <div style="font-size:0.72rem;color:#64748b">{{ $m['desc'] }}</div>
        </div>
      </a>
      @endforeach
    </div>
  </div>
</div>

<div class="card">
  <div class="card-head">
    📋 Surat Terbaru
    <a href="{{ route('persuratan.pengajuan') }}" class="btn btn-primary btn-sm">Lihat Semua</a>
  </div>
  <table>
    <thead>
      <tr><th>Judul Surat</th><th>Jenis</th><th>Sifat</th><th>Progres</th><th>SLA</th><th>Aksi</th></tr>
    </thead>
    <tbody>
      @forelse($suratTerbaru ?? [] as $s)
      <tr>
        <td><b>{{ $s->judul }}</b></td>
        <td>{{ $s->jenis_surat }}</td>
        <td>
          @if($s->sifat==='segera')<span class="badge badge-segera">🔴 Segera</span>
          @elseif($s->sifat==='rahasia')<span class="badge badge-rahasia">🔒 Rahasia</span>
          @else<span class="badge badge-biasa">Biasa</span>@endif
        </td>
        <td>
          @if($s->status==='selesai')<span class="badge badge-selesai">✅ Selesai</span>
          @elseif($s->status==='ditolak')<span class="badge badge-ditolak">❌ Ditolak</span>
          @else<span class="badge badge-proses">🔄 Proses</span>@endif
        </td>
        <td>
          @if(isset($s->sla_terpenuhi))
            @if($s->sla_terpenuhi)<span class="sla-ok">✅ Tepat</span>
            @else<span class="sla-late">⚠️ Terlambat</span>@endif
          @else<span style="color:#94a3b8;font-size:0.74rem">-</span>@endif
        </td>
        <td><a href="{{ route('persuratan.pengajuan.tracking', $s->id) }}" class="btn btn-outline btn-sm">👁 Tracking</a></td>
      </tr>
      @empty
      <tr><td colspan="6" class="empty">Belum ada surat</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection
>>>>>>> ae1b02b (Add full Laravel project fresh)
