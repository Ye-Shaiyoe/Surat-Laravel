@extends('layouts.app')
@section('title','Persediaan')
@section('subtitle','Permintaan dan pengelolaan persediaan kantor')

@section('content')

<div class="tabs">
  <a href="?tab=minta" class="tab {{ request('tab','minta')==='minta'?'active':'' }}">📦 Minta Persediaan</a>
  <a href="?tab=stok" class="tab {{ request('tab')==='stok'?'active':'' }}">📊 Cek Stok</a>
  <a href="?tab=riwayat" class="tab {{ request('tab')==='riwayat'?'active':'' }}">📋 Riwayat</a>
</div>

@if(request('tab','minta')==='minta')

{{-- KATEGORI PERSEDIAAN --}}
<div class="card">
  <div class="card-head">📦 Pilih Kategori Persediaan</div>
  <div class="card-body">
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px">
      @php
      $kategori = [
        ['icon'=>'✏️','nama'=>'ATK','desc'=>'Alat tulis kantor','warna'=>'#dbeafe'],
        ['icon'=>'🖨️','nama'=>'Toner & Tinta','desc'=>'Toner printer & tinta','warna'=>'#dcfce7'],
        ['icon'=>'📄','nama'=>'Kertas','desc'=>'Kertas HVS & amplop','warna'=>'#fef3c7'],
        ['icon'=>'🧴','nama'=>'Kebersihan','desc'=>'Perlengkapan kebersihan','warna'=>'#ede9fe'],
        ['icon'=>'💻','nama'=>'Komputer','desc'=>'Aksesori komputer','warna'=>'#fce7f3'],
        ['icon'=>'📋','nama'=>'Formulir','desc'=>'Formulir & blanko','warna'=>'#fee2e2'],
        ['icon'=>'🔌','nama'=>'Elektronik','desc'=>'Peralatan elektronik','warna'=>'#f0fdf4'],
        ['icon'=>'📦','nama'=>'Lainnya','desc'=>'Persediaan lainnya','warna'=>'#f1f5f9'],
      ];
      @endphp
      @foreach($kategori as $k)
      <div style="background:{{ $k['warna'] }};border-radius:9px;padding:14px;cursor:pointer;text-align:center"
        onclick="pilihPersediaan('{{ $k['nama'] }}')">
        <div style="font-size:1.5rem;margin-bottom:6px">{{ $k['icon'] }}</div>
        <div style="font-size:0.82rem;font-weight:700;color:#0f172a;margin-bottom:2px">{{ $k['nama'] }}</div>
        <div style="font-size:0.69rem;color:#64748b">{{ $k['desc'] }}</div>
      </div>
      @endforeach
    </div>
  </div>
</div>

{{-- FORM PERMINTAAN --}}
<div class="card" id="form-persediaan" style="display:none;max-width:600px">
  <div class="card-head">📦 Form Permintaan — <span id="judul-persediaan"></span></div>
  <div class="card-body">
    <form method="POST" action="{{ route('layanan.persediaan.kirim') }}">
      @csrf
      <input type="hidden" name="kategori" id="input-persediaan"/>

      <div class="form-group">
        <label class="form-label">Kategori</label>
        <input class="form-input" id="tampil-persediaan" type="text" readonly style="background:#f8faff"/>
      </div>
      <div class="form-group">
        <label class="form-label">Nama Barang <span style="color:#ef4444">*</span></label>
        <input class="form-input" type="text" name="nama_barang" placeholder="Contoh: Pulpen hitam, Kertas HVS A4..." required/>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Jumlah <span style="color:#ef4444">*</span></label>
          <input class="form-input" type="number" name="jumlah" placeholder="0" min="1" required/>
        </div>
        <div class="form-group">
          <label class="form-label">Satuan</label>
          <select class="form-input" name="satuan">
            <option>Pcs</option>
            <option>Rim</option>
            <option>Pak</option>
            <option>Lusin</option>
            <option>Unit</option>
            <option>Botol</option>
            <option>Buah</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Keperluan <span style="color:#ef4444">*</span></label>
        <textarea class="form-input" name="keperluan" rows="3" placeholder="Untuk keperluan apa barang ini dibutuhkan?" required></textarea>
      </div>
      <div class="form-group">
        <label class="form-label">Tanggal Diperlukan</label>
        <input class="form-input" type="date" name="tanggal_diperlukan"/>
      </div>

      <div style="display:flex;gap:8px">
        <button type="submit" class="btn btn-primary">📤 Ajukan Permintaan</button>
        <button type="button" class="btn btn-outline" onclick="document.getElementById('form-persediaan').style.display='none'">Batal</button>
      </div>
    </form>
  </div>
</div>

@elseif(request('tab')==='stok')

{{-- CEK STOK --}}
<div class="card">
  <div class="card-head">
    📊 Stok Persediaan
    <div style="display:flex;gap:8px">
      <input class="form-input" type="text" placeholder="Cari barang..." style="width:200px;padding:5px 10px"/>
    </div>
  </div>
  <table>
    <thead>
      <tr><th>Nama Barang</th><th>Kategori</th><th>Stok</th><th>Satuan</th><th>Status</th></tr>
    </thead>
    <tbody>
      @forelse($stok ?? [] as $s)
      <tr>
        <td><b>{{ $s->nama_barang }}</b></td>
        <td>{{ $s->kategori }}</td>
        <td>{{ $s->stok }}</td>
        <td>{{ $s->satuan }}</td>
        <td>
          @if($s->stok <= 0)<span class="badge badge-ditolak">❌ Habis</span>
          @elseif($s->stok <= 5)<span class="badge badge-menunggu">⚠️ Hampir Habis</span>
          @else<span class="badge badge-selesai">✅ Tersedia</span>@endif
        </td>
      </tr>
      @empty
      <tr><td colspan="5" class="empty">Data stok belum tersedia</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

@else

{{-- RIWAYAT --}}
<div class="card">
  <div class="card-head">📋 Riwayat Permintaan Persediaan</div>
  <table>
    <thead>
      <tr><th>Nama Barang</th><th>Kategori</th><th>Jumlah</th><th>Tanggal</th><th>Status</th><th>Aksi</th></tr>
    </thead>
    <tbody>
      @forelse($riwayat ?? [] as $r)
      <tr>
        <td><b>{{ $r->nama_barang }}</b></td>
        <td>{{ $r->kategori }}</td>
        <td>{{ $r->jumlah }} {{ $r->satuan }}</td>
        <td>{{ \Carbon\Carbon::parse($r->created_at)->format('d M Y') }}</td>
        <td>
          @if($r->status==='disetujui')<span class="badge badge-selesai">✅ Disetujui</span>
          @elseif($r->status==='ditolak')<span class="badge badge-ditolak">❌ Ditolak</span>
          @else<span class="badge badge-proses">🔄 Diproses</span>@endif
        </td>
        <td><button class="btn btn-outline btn-sm" onclick="bukaModal('modal-p-{{ $r->id }}')">👁 Detail</button></td>
      </tr>
      <div class="modal-bg" id="modal-p-{{ $r->id }}">
        <div class="modal">
          <h3>📦 Detail Permintaan</h3>
          <table style="font-size:0.81rem;width:100%">
            <tr><td style="color:#64748b;padding:5px 0;width:120px">Nama Barang</td><td><b>{{ $r->nama_barang }}</b></td></tr>
            <tr><td style="color:#64748b;padding:5px 0">Kategori</td><td>{{ $r->kategori }}</td></tr>
            <tr><td style="color:#64748b;padding:5px 0">Jumlah</td><td>{{ $r->jumlah }} {{ $r->satuan }}</td></tr>
            <tr><td style="color:#64748b;padding:5px 0">Keperluan</td><td>{{ $r->keperluan }}</td></tr>
            <tr><td style="color:#64748b;padding:5px 0">Status</td><td>{{ ucfirst($r->status) }}</td></tr>
            @if($r->catatan_admin)<tr><td style="color:#64748b;padding:5px 0">Catatan</td><td>{{ $r->catatan_admin }}</td></tr>@endif
          </table>
          <div class="modal-footer">
            <button class="btn btn-outline" onclick="tutupModal('modal-p-{{ $r->id }}')">Tutup</button>
          </div>
        </div>
      </div>
      @empty
      <tr><td colspan="6" class="empty">Belum ada permintaan persediaan</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

@endif
@endsection

@section('scripts')
<script>
function pilihPersediaan(nama) {
  document.getElementById('input-persediaan').value = nama;
  document.getElementById('tampil-persediaan').value = nama;
  document.getElementById('judul-persediaan').textContent = nama;
  document.getElementById('form-persediaan').style.display = 'block';
  document.getElementById('form-persediaan').scrollIntoView({behavior:'smooth'});
}
</script>
@endsection