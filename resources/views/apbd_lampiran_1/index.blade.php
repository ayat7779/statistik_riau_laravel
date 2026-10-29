@extends('layouts.app')

@section('content')
<div style="margin-bottom: 20px;">
    <h2>Lampiran I - APBD</h2>
</div>

<!-- Year Filter Form -->
<form method="GET" action="" style="margin-bottom: 20px;">
    <label for="tahun" style="font-weight: 500; margin-right: 10px;">Filter Tahun:</label>
    <select name="tahun" id="tahun" onchange="this.form.submit()" style="padding: 6px 12px; border-radius: 4px; border: 1px solid #cbd5e1;">
        @foreach ($available_years as $year)
            <option value="{{ $year }}" {{ $selected_year == $year ? 'selected' : '' }}>
                {{ $year }}
            </option>
        @endforeach
    </select>
</form>

@if(session('success'))
    <div class="message-box message-success" style="padding: 10px; background: #dcfce7; color: #166534; margin-bottom: 15px; border-radius: 4px;">
        {{ session('success') }}
    </div>
@endif

<div>
    <table>
        <thead>
            <tr>
                <th style="text-align: center;">Kode</th>
                <th style="text-align: center;">Uraian</th>
                <th style="text-align: center;">Anggaran</th>
                <th style="text-align: center;">Perubahan</th>
                <th style="text-align: center;">Bertambah/Berkurang</th>
                <th style="text-align: center;">Persentase</th>
                <th style="text-align: center;">Level</th>
                <th style="text-align: center;">Tahapan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $row)
                @php
                    $is_level_1_or_2 = ($row->id_kode_level == 1 || $row->id_kode_level == 2);
                    $tagOpen = $is_level_1_or_2 ? '<strong>' : '';
                    $tagClose = $is_level_1_or_2 ? '</strong>' : '';
                @endphp
                <tr>
                    <td>{!! $tagOpen . $row->kode . $tagClose !!}</td>
                    <td>{!! $tagOpen . $row->uraian . $tagClose !!}</td>
                    <td style="text-align:right">{!! $tagOpen . number_format($row->anggaran ?? 0, 2, ',', '.') . $tagClose !!}</td>
                    <td style="text-align:right">{!! $tagOpen . number_format($row->perubahan ?? 0, 2, ',', '.') . $tagClose !!}</td>
                    <td style="text-align:right">{!! $tagOpen . number_format($row->bertambah_berkurang ?? 0, 2, ',', '.') . $tagClose !!}</td>
                    <td style="text-align:right">{!! $tagOpen . number_format($row->persentase ?? 0, 2, ',', '.') . '%' . $tagClose !!}</td>
                    <td style="text-align:left">{!! $tagOpen . ($row->nama_level ?? '') . $tagClose !!}</td>
                    <td style="text-align:center">{!! $tagOpen . ($row->jenis_apbd ?? '') . $tagClose !!}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">Belum ada data untuk tahun {{ $selected_year }}.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
