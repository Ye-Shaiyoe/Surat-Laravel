@extends('layouts.app')
@section('title','Laporan Persuratan')
@section('subtitle','Rekap bulanan surat — SLA 1 hari kerja')

@section('content')

<div class="card">
  <div class="card-body">
    <form method="GET" style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap">
      <div class="form-group" style="margin:0;flex:1;min-width:110px">
        <label class="form-label">Bulan</label>
        <select class="form-input" name="bulan">
          @foreach(range(1,12) as $b)
          <option value="{{ $b }}" {{ request('bulan',date('n'))==$b?'selected':'' }}>
            {{ \Carbon\Carbon::create()->month($b)->locale('id')->monthName }}
          </option>
          @endforeach
        </select>
      </div>
      <div class="form-group" style="margin:0;flex:1;min-width:90px">
        <label class="form-label">Tahun</label>
        <select class="form-input" name="tahun">
          @foreach([date('Y'),date('Y')-1,date('Y')-2] as $t)
          <option value="{{ $t }}" {{ request('tahun',date('Y'))==$t?'selected':'' }}>{{ $t }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group" style="margin:0;flex:1;min-width:140px">
        <label class="form-label">Jenis Surat</label>
        <select class="form-input" name="jenis">
          <option value="">Semua Jenis</option>
          @foreach(['Nota Dinas','Surat Dinas','Surat Keputusan','Surat Pernyataan','Surat Keterangan'] as $j)
          <option value="{{ $j }}" {{ request('jenis')===$j?'selected':'' }}>{{ $j }}</option>
          @endforeach
        </select>
      </div>
      <div style="display:flex;gap:7px">
        <button type="submit" class="btn btn-primary">🔍 Filter</button>
        <a href="{{ route('persuratan.laporan') }}" class="btn btn-outline">↺ Reset</a>
        <a href="{{ route('persuratan.laporan.export') }}?{{ request()->getQueryString() }}" class="btn btn-outline">📥 Export</a>
      </div>
    </form>
  </div>
</div>

<div class="stats">
  <div class="stat"><div class="stat-icon">📨</div><div><div class="stat-num">{{ $total ?? 0 }}</div><div class="stat-label">Total Surat</div></div></div>
  <div class="stat"><div class="stat-icon">🔄</div><div><div class="stat-num">{{ $proses ?? 0 }}</div><div class="stat-label">Dalam Proses</div></div></div>
  <div class="stat"><div class="stat-icon">✅</div><div><div class="stat-num">{{ $selesai ?? 0 }}</div><div class="stat-label">Selesai</div></div></div>
  <div class="stat"><div class="stat-icon">⚠️</div><div><div class="stat-num">{{ $slaLambat ?? 0 }}</div><div class="stat-label">SLA Terlambat</div></div></div>
</div>

<div class="card">
  <div class="card-head">
    📊 Rekap —
    {{ \Carbon\Carbon::create()->month((int) request('bulan', date('n')))->locale('id')->monthName }}
    {{ request('tahun',date('Y')) }}
    @if(request('jenis'))
    <span style="font-size:0.74rem;font-weight:400;color:#64748b">— {{ request('jenis') }}</span>
    @endif
  </div>
  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>Jenis Surat</th>
        <th>Nama Pengusul</th>
        <th>Judul Surat</th>
        <th>Tujuan</th>
        <th>Nomor Surat</th>
        <th>Tanggal</th>
        <th>Progres</th>
        <th>SLA (1 Hari Kerja)</th>
      </tr>
    </thead>
    <tbody>
      @forelse($laporan ?? [] as $i => $s)
      <tr>
        <td>{{ $i+1 }}</td>
        <td><span style="font-size:0.75rem;font-weight:600">{{ $s->jenis_surat }}</span></td>
        <td>{{ $s->user->name ?? '-' }}</td>
        <td>{{ $s->judul }}</td>
        <td style="font-size:0.76rem">{{ $s->tujuan }}</td>
        <td>{{ $s->nomor_surat ?? '-' }}</td>
        <td style="white-space:nowrap">{{ isset($s->tanggal_surat) ? \Carbon\Carbon::parse($s->tanggal_surat)->format('d M Y') : '-' }}</td>
        <td>
          @if($s->status==='selesai')<span class="badge badge-selesai">✅ Selesai</span>
          @elseif($s->status==='ditolak')<span class="badge badge-ditolak">❌ Ditolak</span>
          @else<span class="badge badge-proses">🔄 Proses ({{ ucfirst($s->status) }})</span>@endif
        </td>
        <td>
          @if(isset($s->sla_terpenuhi))
            @if($s->sla_terpenuhi)<span class="sla-ok">✅ Terpenuhi</span>
            @else<span class="sla-late">⚠️ Tidak Terpenuhi</span>@endif
          @else<span style="color:#94a3b8;font-size:0.73rem">Menunggu</span>@endif
        </td>
      </tr>
      @empty
      <tr><td colspan="9" class="empty">Tidak ada data untuk periode ini</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection