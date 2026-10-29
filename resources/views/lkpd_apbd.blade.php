@extends('layouts.app')

@section('content')
<div style="margin-bottom: 20px;">
    <h2>Lampiran I - LKPD</h2>
</div>

<!-- Year Filter Form -->
<form method="GET" action="" style="margin-bottom: 20px;">
    <label for="tahun_lkpd" style="font-weight: 500; margin-right: 10px;">Filter Tahun LKPD:</label>
    <select name="tahun_lkpd" id="tahun_lkpd" onchange="this.form.submit()" style="padding: 6px 12px; border-radius: 4px; border: 1px solid #cbd5e1;">
        @foreach ($available_years as $year)
            <option value="{{ $year }}" {{ $selected_year == $year ? 'selected' : '' }}>
                {{ $year }}
            </option>
        @endforeach
    </select>
</form>

@if(!empty($result))
    <div>
        <table>
            <thead>
                <tr>
                    <th style="text-align: center;">Kode</th>
                    <th style="text-align: center;">Uraian</th>
                    <th style="text-align: center;">Anggaran</th>
                    <th style="text-align: center;">Realisasi</th>
                    <th style="text-align: center;">Persentase</th>
                    <th style="text-align: center;">Sisa Anggaran</th>
                    <th style="text-align: center;">Nama Level</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($result as $row)
                    @php
                        $is_level_1_or_2 = ($row->id_kode_level == 1 || $row->id_kode_level == 2);
                        $tagOpen = $is_level_1_or_2 ? '<strong>' : '';
                        $tagClose = $is_level_1_or_2 ? '</strong>' : '';
                    @endphp
                    <tr>
                        <td>{!! $tagOpen . $row->kode . $tagClose !!}</td>
                        <td>{!! $tagOpen . $row->uraian . $tagClose !!}</td>
                        <td style="text-align:right">{!! $tagOpen . number_format($row->anggaran ?? 0, 2, ',', '.') . $tagClose !!}</td>
                        <td style="text-align:right">{!! $tagOpen . number_format($row->realisasi ?? 0, 2, ',', '.') . $tagClose !!}</td>
                        <td style="text-align:right">{!! $tagOpen . number_format($row->persentase ?? 0, 2, ',', '.') . '%' . $tagClose !!}</td>
                        <td style="text-align:right">{!! $tagOpen . number_format($row->sisa_anggaran ?? 0, 2, ',', '.') . $tagClose !!}</td>
                        <td style="text-align:left">{!! $tagOpen . ($row->nama_level ?? '') . $tagClose !!}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <p>Belum ada data untuk tahun {{ $selected_year }}.</p>
@endif
@endsection
