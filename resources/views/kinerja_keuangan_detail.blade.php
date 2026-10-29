@extends('layouts.app')

@section('content')
<h2 style="text-align: center;">Detail Kinerja Keuangan Daerah</h2>

<p>Halaman Detail Kinerja Keuangan Daerah memuat indikator dan analisis komprehensif perkembangan keuangan daerah.</p>

<div style="margin-top: 20px;">
    <ul>
        @foreach ($available_years as $year)
            <li>Tahun Data LKPD: <strong>{{ $year }}</strong></li>
        @endforeach
    </ul>
</div>
@endsection
