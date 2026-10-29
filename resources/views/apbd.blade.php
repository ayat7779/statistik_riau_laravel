@extends('layouts.app')

@section('content')
<h2 style="text-align: center;">Lampiran I - APBD</h2>

<form method="GET" action="">
    <label for="tahun">Filter Tahun:</label>
    <select name="tahun" id="tahun" onchange="this.form.submit()">
        <option value="">-- Semua Tahun --</option>
        @foreach ($available_years as $year)
            <option value="{{ $year }}" {{ $selected_year == $year ? 'selected' : '' }}>
                {{ $year }}
            </option>
        @endforeach
    </select>
    <noscript><button type="submit">Terapkan Filter</button></noscript>
</form>

@if(session('message'))
    <div class="message-box message-success">
        {{ session('message') }}
    </div>
@endif

@if(request('error'))
    <div class="message-box message-error">
        Error: {{ request('error') }}
    </div>
@endif

@if(!empty($result))
    <div>
        <table>
            <thead>
                <tr>
                    <th style="display: none;">ID</th>
                    <th style="text-align: center;">Tahun</th>
                    <th style="text-align: center;">Kode</th>
                    <th style="text-align: center;">Uraian</th>
                    <th style="text-align: center;">Anggaran</th>
                    <th style="text-align: center;">Perubahan</th>
                    <th style="text-align: center;">Bertambah/Berkurang</th>
                    <th style="text-align: center;">Persentase</th>
                    <th style="text-align: center;">Level</th>
                    <th style="text-align: center;">Jenis APBD</th>
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
                        <td style="display: none;">{{ $row->id_kode_level }}</td>
                        <td>{!! $tagOpen . $row->tahun . $tagClose !!}</td>
                        <td>{!! $tagOpen . $row->kode . $tagClose !!}</td>
                        <td>{!! $tagOpen . $row->uraian . $tagClose !!}</td>
                        <td style="text-align:right">{!! $tagOpen . number_format($row->anggaran ?? 0, 2, ',', '.') . $tagClose !!}</td>
                        <td style="text-align:right">{!! $tagOpen . number_format($row->perubahan ?? 0, 2, ',', '.') . $tagClose !!}</td>
                        <td style="text-align:right">{!! $tagOpen . number_format($row->bertambah_berkurang ?? 0, 2, ',', '.') . $tagClose !!}</td>
                        <td style="text-align:right">{!! $tagOpen . number_format($row->persentase ?? 0, 2, ',', '.') . '%' . $tagClose !!}</td>
                        <td style="text-align:left">{!! $tagOpen . $row->nama_level . $tagClose !!}</td>
                        <td style="text-align:right">{!! $tagOpen . $row->jenis_apbd . $tagClose !!}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <p>Belum ada data.</p>
@endif
@endsection
