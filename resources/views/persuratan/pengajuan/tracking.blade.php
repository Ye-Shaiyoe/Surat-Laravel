@extends('layouts.app')
@section('title','Tracking Pengajuan')
@section('subtitle','Pantau status pengajuan surat Anda')

@section('content')

<div style="display:grid;grid-template-columns:1fr 1.6fr;gap:16px;align-items:start">

  <div>
    <div class="card">
      <div class="card-head">📄 Detail Surat</div>
      <div class="card-body">
        @php
        $rows = [
          'Judul' => $surat->judul ?? '-',
          'Jenis' => $surat->jenis_surat ?? '-',
          'Tujuan' => $surat->tujuan ?? '-',
          'Nomor Surat' => $surat->nomor_surat ?? '(Belum dinomori)',
          'Tanggal' => isset($surat->tanggal_surat) ? \Carbon\Carbon::parse($surat->tanggal_surat)->format('d M Y') : '-',
        ];
        @endphp
        <table style="font-size:0.8rem">
          @foreach($rows as $key=>$val)
          <tr>
            <td style="color:#64748b;padding:5px 0;width:100px;vertical-align:top">{{ $key }}</td>
            <td style="padding:5px 0"><b>{{ $val }}</b></td>
          </tr>
          @endforeach
          <tr>
            <td style="color:#64748b;padding:5px 0">Sifat</td>
            <td style="padding:5px 0">
              @if(($surat->sifat??'')==='segera')<span class="badge badge-segera">🔴 Segera</span>
              @elseif(($surat->sifat??'')==='rahasia')<span class="badge badge-rahasia">🔒 Rahasia</span>
              @else<span class="badge badge-biasa">Biasa</span>@endif
            </td>
          </tr>
          <tr>
            <td style="color:#64748b;padding:5px 0">SLA</td>
            <td style="padding:5px 0">
              @if(isset($surat->sla_terpenuhi))
                @if($surat->sla_terpenuhi)<span class="sla-ok">✅ Tepat Waktu</span>
                @else<span class="sla-late">⚠️ Terlambat</span>@endif
              @else<span style="color:#94a3b8;font-size:0.74rem">Dalam proses</span>@endif
            </td>
          </tr>
        </table>

        @if(($surat->status ?? '') === 'follow_up')
        <div style="background:#fef3c7;border:1px solid #f59e0b;border-radius:7px;padding:10px;margin-top:12px;font-size:0.78rem;color:#92400e">
          ℹ️ <b>Perlu Follow Up</b> — Surat ini memerlukan tindak lanjut dari Anda.
        </div>
        @endif

        @if(($surat->status ?? '') === 'menunggu_hasil')
        <div style="background:#dbeafe;border:1px solid #1e40af;border-radius:7px;padding:10px;margin-top:12px;font-size:0.78rem;color:#1e40af">
          ⏳ <b>Menunggu Hasil</b> — Surat sedang menunggu hasil balasan.
        </div>
        @endif

        @if($surat->file_path ?? false)
        <div style="margin-top:12px">
          <a href="{{ asset('storage/'.$surat->file_path) }}" target="_blank" class="btn btn-outline btn-sm">📥 Download Surat</a>
        </div>
        @endif
      </div>
    </div>

    <div style="margin-top:8px">
      <a href="{{ route('persuratan.pengajuan') }}?tab=riwayat" class="btn btn-outline btn-sm">← Kembali</a>
    </div>
  </div>

  <div class="card">
    <div class="card-head">🔄 Tracking Status Pengajuan</div>
    <div class="card-body">
      @php
      $tahapan = [
        ['key'=>'diajukan',    'label'=>'Usulan Diajukan',           'desc'=>'Surat telah diajukan oleh pegawai pengusul'],
        ['key'=>'arsiparis',   'label'=>'Verifikasi Arsiparis',      'desc'=>'Sedang diverifikasi oleh arsiparis'],
        ['key'=>'kasubbag',    'label'=>'Verifikasi Kasubbag TU',    'desc'=>'Sedang diverifikasi oleh Kasubbag TU'],
        ['key'=>'kabalai',     'label'=>'Persetujuan Kepala Balai',  'desc'=>'Menunggu persetujuan Kepala Balai'],
        ['key'=>'penomoran',   'label'=>'Penomoran Surat',           'desc'=>'Surat sedang diberi nomor oleh bagian persuratan'],
        ['key'=>'ds',          'label'=>'DS Surat',                  'desc'=>'Proses penandatanganan digital (DS) surat'],
        ['key'=>'tnde',        'label'=>'Pengiriman via TNDe',       'desc'=>'Surat dikirim melalui sistem TNDe'],
        ['key'=>'srikandi',    'label'=>'Pengiriman via Srikandi',   'desc'=>'Surat dikirim melalui sistem Srikandi'],
        ['key'=>'pengarsipan', 'label'=>'Pengarsipan',               'desc'=>'Surat sedang diarsipkan oleh bagian persuratan'],
        ['key'=>'selesai',     'label'=>'Selesai',                   'desc'=>'Proses pengajuan surat telah selesai'],
      ];

      $statusSaat  = $surat->status ?? 'diajukan';
      $urutan      = array_column($tahapan, 'key');
      $indexSaat   = array_search($statusSaat, $urutan);
      if ($indexSaat === false) $indexSaat = 0;
      @endphp

      <div class="tracking">
        @foreach($tahapan as $i => $t)
        @php
          $isDone   = $i < $indexSaat;
          $isActive = $i === $indexSaat;
          $cls      = $isDone ? 'done' : ($isActive ? 'active' : 'pending');
        @endphp
        <div class="track-item">
          <div class="track-dot {{ $cls }}">{{ $isDone ? '✓' : ($i+1) }}</div>
          <div class="track-content">
            <div class="track-title {{ $cls }}">{{ $t['label'] }}</div>
            <div class="track-desc">{{ $t['desc'] }}</div>
            @if($isDone && isset($surat->riwayat[$t['key']]))
            <div class="track-time">{{ \Carbon\Carbon::parse($surat->riwayat[$t['key']])->format('d M Y, H:i') }}</div>
            @endif
            @if($isActive)
            <div style="margin-top:4px">
              <span style="background:#dbeafe;color:#1d4ed8;font-size:0.68rem;font-weight:700;padding:2px 8px;border-radius:10px">● Sedang diproses</span>
            </div>
            @endif
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>

</div>
@endsection