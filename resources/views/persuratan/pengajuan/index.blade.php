@extends('layouts.app')
@section('title','Pengajuan Surat')
@section('subtitle','Ajukan surat dan pantau status pengajuan')

@section('content')

<div class="tabs">
  <a href="?tab=form" class="tab {{ request('tab','form')==='form'?'active':'' }}">✉️ Ajukan Surat</a>
  <a href="?tab=riwayat" class="tab {{ request('tab')==='riwayat'?'active':'' }}">📋 Riwayat Pengajuan</a>
</div>

@if(request('tab','form')==='form')

<div class="card" style="max-width:640px">
  <div class="card-head">✉️ Form Pengajuan Surat</div>
  <div class="card-body">
    <form method="POST" action="{{ route('persuratan.pengajuan.kirim') }}" enctype="multipart/form-data">
      @csrf

      <div style="background:#f8faff;border-radius:8px;padding:14px;margin-bottom:14px">
        <div style="font-size:0.74rem;font-weight:700;color:#1e40af;margin-bottom:12px">📝 INFORMASI SURAT</div>

        <div class="form-group">
          <label class="form-label">Jenis Surat <span>*</span></label>
          <select class="form-input" name="jenis_surat" required>
            <option value="">-- Pilih Jenis Surat --</option>
            <option {{ old('jenis_surat')==='Nota Dinas'?'selected':'' }}>Nota Dinas</option>
            <option {{ old('jenis_surat')==='Surat Dinas'?'selected':'' }}>Surat Dinas</option>
            <option {{ old('jenis_surat')==='Surat Keputusan'?'selected':'' }}>Surat Keputusan</option>
            <option {{ old('jenis_surat')==='Surat Pernyataan'?'selected':'' }}>Surat Pernyataan</option>
            <option {{ old('jenis_surat')==='Surat Keterangan'?'selected':'' }}>Surat Keterangan</option>
          </select>
          @error('jenis_surat')<div style="color:#ef4444;font-size:0.73rem;margin-top:3px">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
          <label class="form-label">Judul Surat <span>*</span></label>
          <input class="form-input" type="text" name="judul" placeholder="Masukkan judul surat..." required value="{{ old('judul') }}"/>
          @error('judul')<div style="color:#ef4444;font-size:0.73rem;margin-top:3px">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
          <label class="form-label">Tujuan Surat <span>*</span></label>
          <input class="form-input" type="text" name="tujuan" placeholder="Kepada Yth..." required value="{{ old('tujuan') }}"/>
          @error('tujuan')<div style="color:#ef4444;font-size:0.73rem;margin-top:3px">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
          <label class="form-label">Sifat Surat <span>*</span></label>
          <div style="display:flex;gap:16px;margin-top:4px">
            @foreach(['biasa'=>'Biasa','segera'=>'🔴 Segera','rahasia'=>'🔒 Rahasia'] as $val=>$label)
            <label style="display:flex;align-items:center;gap:6px;font-size:0.83rem;cursor:pointer">
              <input type="radio" name="sifat" value="{{ $val }}" {{ old('sifat','biasa')===$val?'checked':'' }} required/>
              {{ $label }}
            </label>
            @endforeach
          </div>
          @error('sifat')<div style="color:#ef4444;font-size:0.73rem;margin-top:3px">{{ $message }}</div>@enderror
        </div>
      </div>

      <div style="background:#f8faff;border-radius:8px;padding:14px;margin-bottom:14px">
        <div style="font-size:0.74rem;font-weight:700;color:#1e40af;margin-bottom:12px">📎 UPLOAD DOKUMEN</div>

        <div class="form-group">
          <label class="form-label">File Surat <span>*</span> <small style="font-weight:400;text-transform:none;color:#94a3b8">(.docx)</small></label>
          <div class="upload-box" onclick="document.getElementById('file-surat').click()">
            📘 <span id="label-surat">Klik untuk pilih file surat (.docx)</span>
          </div>
          <input type="file" id="file-surat" name="file_surat" accept=".docx,.doc" style="display:none"
            onchange="document.getElementById('label-surat').textContent='✅ '+this.files[0].name"/>
          @error('file_surat')<div style="color:#ef4444;font-size:0.73rem;margin-top:3px">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
          <label class="form-label">Lampiran <small style="font-weight:400;text-transform:none;color:#94a3b8">(opsional, bisa lebih dari 1 file)</small></label>
          <div class="upload-box" onclick="document.getElementById('file-lampiran').click()">
            📎 <span id="label-lampiran">Klik untuk pilih lampiran</span>
          </div>
          <input type="file" id="file-lampiran" name="lampiran[]" multiple style="display:none" onchange="updateLampiran(this)"/>
          <div id="list-lampiran" style="margin-top:6px;font-size:0.74rem;color:#64748b"></div>
        </div>
      </div>

      <button type="submit" class="btn btn-primary">📤 Ajukan Surat</button>
    </form>
  </div>
</div>

@else

<div class="card">
  <div class="card-head">📋 Riwayat Pengajuan</div>
  <table>
    <thead>
      <tr><th>Judul Surat</th><th>Jenis</th><th>Sifat</th><th>Tujuan</th><th>Progres</th><th>Aksi</th></tr>
    </thead>
    <tbody>
      @forelse($riwayat ?? [] as $s)
      <tr>
        <td><b>{{ $s->judul }}</b></td>
        <td>{{ $s->jenis_surat }}</td>
        <td>
          @if($s->sifat==='segera')<span class="badge badge-segera">🔴 Segera</span>
          @elseif($s->sifat==='rahasia')<span class="badge badge-rahasia">🔒 Rahasia</span>
          @else<span class="badge badge-biasa">Biasa</span>@endif
        </td>
        <td>{{ $s->tujuan }}</td>
        <td>
          @if($s->status==='selesai')<span class="badge badge-selesai">✅ Selesai</span>
          @elseif($s->status==='ditolak')<span class="badge badge-ditolak">❌ Ditolak</span>
          @else<span class="badge badge-proses">🔄 Proses</span>@endif
        </td>
        <td><a href="{{ route('persuratan.pengajuan.tracking', $s->id) }}" class="btn btn-outline btn-sm">👁 Tracking</a></td>
      </tr>
      @empty
      <tr><td colspan="6" class="empty">Belum ada pengajuan surat</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

@endif
@endsection

@section('scripts')
<script>
function updateLampiran(input) {
  const label = document.getElementById('label-lampiran');
  const list = document.getElementById('list-lampiran');
  if (input.files.length > 0) {
    label.textContent = '✅ ' + input.files.length + ' file dipilih';
    list.innerHTML = Array.from(input.files).map(f=>`<div>📎 ${f.name}</div>`).join('');
  }
}
</script>
@endsection