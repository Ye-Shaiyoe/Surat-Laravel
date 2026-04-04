@extends('layouts.app')
@section('title','Format Surat')
@section('subtitle','Download template surat resmi Direktorat Metrologi')

@section('content')

<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px">
  @php
  $format = [
    ['icon'=>'📘','nama'=>'Nota Dinas','desc'=>'Digunakan untuk komunikasi internal antar unit kerja','warna'=>'#dbeafe'],
    ['icon'=>'📘','nama'=>'Surat Dinas','desc'=>'Surat resmi untuk keperluan kedinasan eksternal','warna'=>'#dbeafe'],
    ['icon'=>'📘','nama'=>'Surat Keputusan','desc'=>'Surat penetapan keputusan oleh pejabat berwenang','warna'=>'#ede9fe'],
    ['icon'=>'📘','nama'=>'Surat Pernyataan','desc'=>'Surat pernyataan resmi dari pegawai','warna'=>'#dcfce7'],
    ['icon'=>'📘','nama'=>'Surat Keterangan','desc'=>'Surat keterangan untuk keperluan tertentu','warna'=>'#fef3c7'],
  ];
  @endphp

  @foreach($format as $f)
  <div class="card">
    <div style="background:{{ $f['warna'] }};padding:20px;text-align:center">
      <div style="font-size:2.2rem">{{ $f['icon'] }}</div>
    </div>
    <div class="card-body">
      <div style="font-weight:700;font-size:0.88rem;margin-bottom:4px">{{ $f['nama'] }}</div>
      <div style="font-size:0.74rem;color:#64748b;margin-bottom:4px;line-height:1.5">{{ $f['desc'] }}</div>
      <div style="font-size:0.68rem;color:#94a3b8;margin-bottom:12px">📄 Word (.docx)</div>
      <a href="#" class="btn btn-primary btn-sm">📥 Download Template</a>
    </div>
  </div>
  @endforeach
</div>

@endsection