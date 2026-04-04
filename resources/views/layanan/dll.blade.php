@extends('layouts.app')
@section('title','Layanan Lainnya')
@section('subtitle','Layanan ketatausahaan lainnya')

@section('content')

<div class="tabs">
  <a href="?tab=ajukan" class="tab {{ request('tab','ajukan')==='ajukan'?'active':'' }}">📝 Ajukan</a>
  <a href="?tab=riwayat" class="tab {{ request('tab')==='riwayat'?'active':'' }}">📋 Riwayat</a>
</div>

@if(request('tab','ajukan')==='ajukan')

<div class="card">
  <div class="card-head">📋 Pilih Jenis Layanan</div>
  <div class="card-body">
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px">
      @php
      $layanan = [
        ['icon'=>'📅','nama'=>'Peminjaman Ruangan','desc'=>'Ajukan peminjaman ruang rapat/aula','warna'=>'#dbeafe'],
        ['icon'=>'🚗','nama'=>'Peminjaman Kendaraan','desc'=>'Ajukan peminjaman kendaraan dinas','warna'=>'#dcfce7'],
        ['icon'=>'🔑','nama'=>'Permintaan Akses','desc'=>'Ajukan permintaan akses sistem/ruangan','warna'=>'#fef3c7'],
        ['icon'=>'📢','nama'=>'Pengumuman','desc'=>'Ajukan pemasangan pengumuman','warna'=>'#ede9fe'],
        ['icon'=>'🖨️','nama'=>'Permintaan Cetak','desc'=>'Ajukan permintaan pencetakan dokumen','warna'=>'#fce7f3'],
        ['icon'=>'❓','nama'=>'Lainnya','desc'=>'Layanan lain yang tidak tercantum','warna'=>'#f1f5f9'],
      ];
      @endphp
      @foreach($layanan as $l)
      <div style="background:{{ $l['warna'] }};border-radius:9px;padding:16px;cursor:pointer"
        onclick="pilihLayananLain('{{ $l['nama'] }}')">
        <div style="font-size:1.6rem;margin-bottom:8px">{{ $l['icon'] }}</div>
        <div style="font-size:0.85rem;font-weight:700;color:#0f172a;margin-bottom:3px">{{ $l['nama'] }}</div>
        <div style="font-size:0.72rem;color:#64748b">{{ $l['desc'] }}</div>
      </div>
      @endforeach
    </div>
  </div>
</div>

<div class="card" id="form-dll" style="display:none;max-width:600px">
  <div class="card-head">📝 Form Pengajuan — <span id="judul-dll"></span></div>
  <div class="card-body">
    <form method="POST" action="{{ route('layanan.dll.kirim') }}" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="jenis" id="input-dll"/>

      <div class="form-group">
        <label class="form-label">Jenis Layanan</label>
        <input class="form-input" id="tampil-dll" type="text" readonly style="background:#f8faff"/>
      </div>
      <div class="form-group">
        <label class="form-label">Pemohon</label>
        <input class="form-input" type="text" value="{{ auth()->user()->name }}" readonly style="background:#f8faff"/>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Tanggal Mulai <span style="color:#ef4444">*</span></label>
          <input class="form-input" type="date" name="tanggal_mulai" required/>
        </div>
        <div class="form-group">
          <label class="form-label">Tanggal Selesai</label>
          <input class="form-input" type="date" name="tanggal_selesai"/>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Keperluan / Keterangan <span style="color:#ef4444">*</span></label>
        <textarea class="form-input" name="keterangan" rows="3" placeholder="Jelaskan keperluan secara detail..." required></textarea>
      </div>
      <div class="form-group">
        <label class="form-label">Dokumen Pendukung <small style="font-weight:400;text-transform:none;color:#94a3b8">(opsional)</small></label>
        <div class="upload-box" onclick="document.getElementById('dok-dll').click()">
          📎 <span id="label-dll">Klik untuk upload dokumen</span>
        </div>
        <input type="file" id="dok-dll" name="dokumen" style="display:none"
          onchange="document.getElementById('label-dll').textContent='✅ '+this.files[0].name"/>
      </div>

      <div style="display:flex;gap:8px">
        <button type="submit" class="btn btn-primary">📤 Ajukan</button>
        <button type="button" class="btn btn-outline" onclick="document.getElementById('form-dll').style.display='none'">Batal</button>
      </div>
    </form>
  </div>
</div>

@else

<div class="card">
  <div class="card-head">📋 Riwayat Pengajuan</div>
  <table>
    <thead>
      <tr><th>Jenis Layanan</th><th>Keterangan</th><th>Tgl Mulai</th><th>Status</th><th>Aksi</th></tr>
    </thead>
    <tbody>
      @forelse($riwayat ?? [] as $r)
      <tr>
        <td><b>{{ $r->jenis }}</b></td>
        <td>{{ Str::limit($r->keterangan, 40) }}</td>
        <td>{{ \Carbon\Carbon::parse($r->tanggal_mulai)->format('d M Y') }}</td>
        <td>
          @if($r->status==='selesai')<span class="badge badge-selesai">✅ Selesai</span>
          @elseif($r->status==='ditolak')<span class="badge badge-ditolak">❌ Ditolak</span>
          @else<span class="badge badge-proses">🔄 Diproses</span>@endif
        </td>
        <td><button class="btn btn-outline btn-sm" onclick="bukaModal('modal-dll-{{ $r->id }}')">👁 Detail</button></td>
      </tr>
      <div class="modal-bg" id="modal-dll-{{ $r->id }}">
        <div class="modal">
          <h3>📋 Detail Pengajuan</h3>
          <table style="font-size:0.81rem;width:100%">
            <tr><td style="color:#64748b;padding:5px 0;width:120px">Jenis</td><td><b>{{ $r->jenis }}</b></td></tr>
            <tr><td style="color:#64748b;padding:5px 0">Keterangan</td><td>{{ $r->keterangan }}</td></tr>
            <tr><td style="color:#64748b;padding:5px 0">Tgl Mulai</td><td>{{ \Carbon\Carbon::parse($r->tanggal_mulai)->format('d M Y') }}</td></tr>
            @if($r->tanggal_selesai)<tr><td style="color:#64748b;padding:5px 0">Tgl Selesai</td><td>{{ \Carbon\Carbon::parse($r->tanggal_selesai)->format('d M Y') }}</td></tr>@endif
            <tr><td style="color:#64748b;padding:5px 0">Status</td><td>{{ ucfirst($r->status) }}</td></tr>
            @if($r->catatan_admin)<tr><td style="color:#64748b;padding:5px 0">Catatan</td><td>{{ $r->catatan_admin }}</td></tr>@endif
          </table>
          <div class="modal-footer">
            <button class="btn btn-outline" onclick="tutupModal('modal-dll-{{ $r->id }}')">Tutup</button>
          </div>
        </div>
      </div>
      @empty
      <tr><td colspan="5" class="empty">Belum ada pengajuan</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

@endif
@endsection

@section('scripts')
<script>
function pilihLayananLain(nama) {
  document.getElementById('input-dll').value = nama;
  document.getElementById('tampil-dll').value = nama;
  document.getElementById('judul-dll').textContent = nama;
  document.getElementById('form-dll').style.display = 'block';
  document.getElementById('form-dll').scrollIntoView({behavior:'smooth'});
}
</script>
@endsection