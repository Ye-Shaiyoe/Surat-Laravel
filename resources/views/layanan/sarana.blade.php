@extends('layouts.app')
@section('title','Sarana & Prasarana')
@section('subtitle','Pengajuan laporan sarana dan prasarana')

@section('content')

<div class="tabs">
  <a href="?tab=ajukan" class="tab {{ request('tab','ajukan')==='ajukan'?'active':'' }}">📝 Ajukan Laporan</a>
  <a href="?tab=riwayat" class="tab {{ request('tab')==='riwayat'?'active':'' }}">📋 Riwayat</a>
</div>

@if(request('tab','ajukan')==='ajukan')

{{-- KATEGORI --}}
<div class="card">
  <div class="card-head">🏗️ Pilih Kategori Laporan</div>
  <div class="card-body">
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px">
      @php
      $kategori = [
        ['icon'=>'🔧','nama'=>'Kerusakan Aset','desc'=>'Laporkan aset yang rusak atau perlu perbaikan','warna'=>'#fee2e2'],
        ['icon'=>'🖥️','nama'=>'Permintaan Peralatan','desc'=>'Ajukan permintaan peralatan kantor','warna'=>'#dbeafe'],
        ['icon'=>'🏢','nama'=>'Pemeliharaan Gedung','desc'=>'Laporan pemeliharaan gedung & fasilitas','warna'=>'#dcfce7'],
        ['icon'=>'🚗','nama'=>'Kendaraan Dinas','desc'=>'Pengajuan penggunaan kendaraan dinas','warna'=>'#fef3c7'],
        ['icon'=>'💡','nama'=>'Utilitas','desc'=>'Laporan listrik, air, dan utilitas lainnya','warna'=>'#ede9fe'],
        ['icon'=>'📋','nama'=>'Lainnya','desc'=>'Laporan sarana prasarana lainnya','warna'=>'#f1f5f9'],
      ];
      @endphp
      @foreach($kategori as $k)
      <div style="background:{{ $k['warna'] }};border-radius:9px;padding:16px;cursor:pointer"
        onclick="pilihKategori('{{ $k['nama'] }}')">
        <div style="font-size:1.6rem;margin-bottom:8px">{{ $k['icon'] }}</div>
        <div style="font-size:0.85rem;font-weight:700;color:#0f172a;margin-bottom:3px">{{ $k['nama'] }}</div>
        <div style="font-size:0.72rem;color:#64748b">{{ $k['desc'] }}</div>
      </div>
      @endforeach
    </div>
  </div>
</div>

{{-- FORM --}}
<div class="card" id="form-sarana" style="display:none;max-width:600px">
  <div class="card-head">📝 Form Laporan — <span id="judul-kategori"></span></div>
  <div class="card-body">
    <form method="POST" action="{{ route('layanan.sarana.kirim') }}" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="kategori" id="input-kategori"/>

      <div class="form-group">
        <label class="form-label">Kategori</label>
        <input class="form-input" id="tampil-kategori" type="text" readonly style="background:#f8faff"/>
      </div>
      <div class="form-group">
        <label class="form-label">Nama Aset / Lokasi <span style="color:#ef4444">*</span></label>
        <input class="form-input" type="text" name="nama_aset" placeholder="Contoh: Komputer Ruang A / Gedung Utama Lt.2" required/>
      </div>
      <div class="form-group">
        <label class="form-label">Deskripsi Masalah / Keperluan <span style="color:#ef4444">*</span></label>
        <textarea class="form-input" name="deskripsi" rows="3" placeholder="Jelaskan masalah atau keperluan secara detail..." required></textarea>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Prioritas</label>
          <select class="form-input" name="prioritas">
            <option value="rendah">🟢 Rendah</option>
            <option value="sedang" selected>🟡 Sedang</option>
            <option value="tinggi">🔴 Tinggi</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Tanggal Diperlukan</label>
          <input class="form-input" type="date" name="tanggal_diperlukan"/>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Foto / Dokumen <small style="font-weight:400;text-transform:none;color:#94a3b8">(opsional)</small></label>
        <div class="upload-box" onclick="document.getElementById('foto-sarana').click()">
          📸 <span id="label-foto">Klik untuk upload foto atau dokumen</span>
        </div>
        <input type="file" id="foto-sarana" name="foto" accept="image/*,.pdf,.docx" style="display:none"
          onchange="document.getElementById('label-foto').textContent='✅ '+this.files[0].name"/>
      </div>

      <div style="display:flex;gap:8px">
        <button type="submit" class="btn btn-primary">📤 Kirim Laporan</button>
        <button type="button" class="btn btn-outline" onclick="document.getElementById('form-sarana').style.display='none'">Batal</button>
      </div>
    </form>
  </div>
</div>

@else

<div class="card">
  <div class="card-head">📋 Riwayat Laporan Sarana & Prasarana</div>
  <table>
    <thead>
      <tr><th>Kategori</th><th>Nama Aset</th><th>Prioritas</th><th>Tgl Lapor</th><th>Status</th><th>Aksi</th></tr>
    </thead>
    <tbody>
      @forelse($riwayat ?? [] as $r)
      <tr>
        <td><b>{{ $r->kategori }}</b></td>
        <td>{{ $r->nama_aset }}</td>
        <td>
          @if($r->prioritas==='tinggi')<span class="badge badge-segera">🔴 Tinggi</span>
          @elseif($r->prioritas==='sedang')<span style="background:#fef3c7;color:#d97706" class="badge">🟡 Sedang</span>
          @else<span class="badge badge-biasa">🟢 Rendah</span>@endif
        </td>
        <td>{{ \Carbon\Carbon::parse($r->created_at)->format('d M Y') }}</td>
        <td>
          @if($r->status==='selesai')<span class="badge badge-selesai">✅ Selesai</span>
          @elseif($r->status==='ditolak')<span class="badge badge-ditolak">❌ Ditolak</span>
          @else<span class="badge badge-proses">🔄 Diproses</span>@endif
        </td>
        <td><button class="btn btn-outline btn-sm" onclick="bukaModal('modal-sp-{{ $r->id }}')">👁 Detail</button></td>
      </tr>
      <div class="modal-bg" id="modal-sp-{{ $r->id }}">
        <div class="modal">
          <h3>🏗️ Detail Laporan</h3>
          <table style="font-size:0.81rem;width:100%">
            <tr><td style="color:#64748b;padding:5px 0;width:130px">Kategori</td><td><b>{{ $r->kategori }}</b></td></tr>
            <tr><td style="color:#64748b;padding:5px 0">Nama Aset</td><td>{{ $r->nama_aset }}</td></tr>
            <tr><td style="color:#64748b;padding:5px 0">Deskripsi</td><td>{{ $r->deskripsi }}</td></tr>
            <tr><td style="color:#64748b;padding:5px 0">Prioritas</td><td>{{ ucfirst($r->prioritas) }}</td></tr>
            <tr><td style="color:#64748b;padding:5px 0">Status</td><td>{{ ucfirst($r->status) }}</td></tr>
            @if($r->catatan_admin)<tr><td style="color:#64748b;padding:5px 0">Catatan</td><td>{{ $r->catatan_admin }}</td></tr>@endif
          </table>
          <div class="modal-footer">
            <button class="btn btn-outline" onclick="tutupModal('modal-sp-{{ $r->id }}')">Tutup</button>
          </div>
        </div>
      </div>
      @empty
      <tr><td colspan="6" class="empty">Belum ada laporan sarana & prasarana</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

@endif
@endsection

@section('scripts')
<script>
function pilihKategori(nama) {
  document.getElementById('input-kategori').value = nama;
  document.getElementById('tampil-kategori').value = nama;
  document.getElementById('judul-kategori').textContent = nama;
  document.getElementById('form-sarana').style.display = 'block';
  document.getElementById('form-sarana').scrollIntoView({behavior:'smooth'});
}
</script>
@endsection