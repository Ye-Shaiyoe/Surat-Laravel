@extends('layouts.app')
@section('title','Layanan Ketatausahaan')
@section('subtitle','Pengajuan layanan ketatausahaan Direktorat Metrologi')

@section('content')

<div class="tabs">
  <a href="?tab=ajukan" class="tab {{ request('tab','ajukan')==='ajukan'?'active':'' }}">📝 Ajukan Layanan</a>
  <a href="?tab=riwayat" class="tab {{ request('tab')==='riwayat'?'active':'' }}">📋 Riwayat</a>
</div>

@if(request('tab','ajukan')==='ajukan')

{{-- JENIS LAYANAN --}}
<div class="card">
  <div class="card-head">📋 Pilih Jenis Layanan</div>
  <div class="card-body">
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px">
      @php
      $layanan = [
        ['icon'=>'📝','nama'=>'Surat Tugas','desc'=>'Pengajuan surat tugas dinas','warna'=>'#dbeafe'],
        ['icon'=>'🏖️','nama'=>'Cuti Pegawai','desc'=>'Pengajuan permohonan cuti','warna'=>'#dcfce7'],
        ['icon'=>'📜','nama'=>'SK Pegawai','desc'=>'Pengajuan surat keputusan pegawai','warna'=>'#ede9fe'],
        ['icon'=>'🎓','nama'=>'Surat Izin Belajar','desc'=>'Pengajuan izin belajar/tugas belajar','warna'=>'#fef3c7'],
        ['icon'=>'🏥','nama'=>'Surat Keterangan Sakit','desc'=>'Pengajuan surat keterangan sakit','warna'=>'#fce7f3'],
        ['icon'=>'📋','nama'=>'Lainnya','desc'=>'Layanan ketatausahaan lainnya','warna'=>'#f1f5f9'],
      ];
      @endphp
      @foreach($layanan as $l)
      <div style="background:{{ $l['warna'] }};border-radius:9px;padding:16px;cursor:pointer;border:1.5px solid transparent"
        onclick="pilihLayanan('{{ $l['nama'] }}')"
        id="card-{{ Str::slug($l['nama']) }}">
        <div style="font-size:1.6rem;margin-bottom:8px">{{ $l['icon'] }}</div>
        <div style="font-size:0.85rem;font-weight:700;color:#0f172a;margin-bottom:3px">{{ $l['nama'] }}</div>
        <div style="font-size:0.72rem;color:#64748b">{{ $l['desc'] }}</div>
      </div>
      @endforeach
    </div>
  </div>
</div>

{{-- FORM PENGAJUAN --}}
<div class="card" id="form-layanan" style="display:none;max-width:600px">
  <div class="card-head">
    📝 Form Pengajuan — <span id="judul-layanan"></span>
  </div>
  <div class="card-body">
    <form method="POST" action="{{ route('layanan.ketatausahaan.kirim') }}" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="jenis_layanan" id="input-jenis"/>

      <div class="form-group">
        <label class="form-label">Jenis Layanan</label>
        <input class="form-input" id="tampil-jenis" type="text" readonly style="background:#f8faff"/>
      </div>
      <div class="form-group">
        <label class="form-label">Nama Pemohon <span style="color:#ef4444">*</span></label>
        <input class="form-input" type="text" name="nama_pemohon" value="{{ auth()->user()->name }}" readonly style="background:#f8faff"/>
      </div>
      <div class="form-group">
        <label class="form-label">Keterangan / Keperluan <span style="color:#ef4444">*</span></label>
        <textarea class="form-input" name="keterangan" rows="3" placeholder="Jelaskan keperluan pengajuan..." required></textarea>
      </div>
      <div class="form-group">
        <label class="form-label">Tanggal Diperlukan <span style="color:#ef4444">*</span></label>
        <input class="form-input" type="date" name="tanggal_diperlukan" required/>
      </div>
      <div class="form-group">
        <label class="form-label">Dokumen Pendukung <small style="font-weight:400;text-transform:none;color:#94a3b8">(opsional)</small></label>
        <div class="upload-box" onclick="document.getElementById('dok-pendukung').click()">
          📎 <span id="label-dok">Klik untuk upload dokumen</span>
        </div>
        <input type="file" id="dok-pendukung" name="dokumen" style="display:none"
          onchange="document.getElementById('label-dok').textContent='✅ '+this.files[0].name"/>
      </div>

      @if($errors->any())
      <div style="color:#ef4444;font-size:0.78rem;margin-bottom:10px">{{ $errors->first() }}</div>
      @endif

      <div style="display:flex;gap:8px">
        <button type="submit" class="btn btn-primary">📤 Ajukan Layanan</button>
        <button type="button" class="btn btn-outline" onclick="tutupForm()">Batal</button>
      </div>
    </form>
  </div>
</div>

@else

{{-- RIWAYAT --}}
<div class="card">
  <div class="card-head">📋 Riwayat Pengajuan Layanan</div>
  <table>
    <thead>
      <tr><th>Jenis Layanan</th><th>Keterangan</th><th>Tgl Diperlukan</th><th>Status</th><th>Aksi</th></tr>
    </thead>
    <tbody>
      @forelse($riwayat ?? [] as $r)
      <tr>
        <td><b>{{ $r->jenis_layanan }}</b></td>
        <td>{{ Str::limit($r->keterangan, 40) }}</td>
        <td>{{ \Carbon\Carbon::parse($r->tanggal_diperlukan)->format('d M Y') }}</td>
        <td>
          @if($r->status==='selesai')<span class="badge badge-selesai">✅ Selesai</span>
          @elseif($r->status==='ditolak')<span class="badge badge-ditolak">❌ Ditolak</span>
          @else<span class="badge badge-proses">🔄 Diproses</span>@endif
        </td>
        <td><button class="btn btn-outline btn-sm" onclick="bukaModal('modal-detail-{{ $r->id }}')">👁 Detail</button></td>
      </tr>

      {{-- Modal Detail --}}
      <div class="modal-bg" id="modal-detail-{{ $r->id }}">
        <div class="modal">
          <h3>📋 Detail Pengajuan</h3>
          <table style="font-size:0.81rem;width:100%">
            <tr><td style="color:#64748b;padding:5px 0;width:130px">Jenis Layanan</td><td><b>{{ $r->jenis_layanan }}</b></td></tr>
            <tr><td style="color:#64748b;padding:5px 0">Keterangan</td><td>{{ $r->keterangan }}</td></tr>
            <tr><td style="color:#64748b;padding:5px 0">Tgl Diperlukan</td><td>{{ \Carbon\Carbon::parse($r->tanggal_diperlukan)->format('d M Y') }}</td></tr>
            <tr><td style="color:#64748b;padding:5px 0">Status</td><td>{{ ucfirst($r->status) }}</td></tr>
            @if($r->catatan_admin)<tr><td style="color:#64748b;padding:5px 0">Catatan Admin</td><td>{{ $r->catatan_admin }}</td></tr>@endif
          </table>
          <div class="modal-footer">
            <button class="btn btn-outline" onclick="tutupModal('modal-detail-{{ $r->id }}')">Tutup</button>
          </div>
        </div>
      </div>
      @empty
      <tr><td colspan="5" class="empty">Belum ada pengajuan layanan</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

@endif
@endsection

@section('scripts')
<script>
function pilihLayanan(nama) {
  document.getElementById('input-jenis').value = nama;
  document.getElementById('tampil-jenis').value = nama;
  document.getElementById('judul-layanan').textContent = nama;
  document.getElementById('form-layanan').style.display = 'block';
  document.getElementById('form-layanan').scrollIntoView({behavior:'smooth'});
}
function tutupForm() {
  document.getElementById('form-layanan').style.display = 'none';
}
</script>
@endsection