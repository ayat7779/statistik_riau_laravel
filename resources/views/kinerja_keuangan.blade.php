@extends('layouts.app')

@section('content')
<h2 style="text-align: center;">Kinerja Keuangan Daerah</h2>

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
</form>

@if(!empty($result))
    <div>
        <table>
            <thead>
                <tr>
                    <th style="text-align: center;">Tahun</th>
                    <th style="text-align: center;">Kinerja Keuangan</th>
                    <th style="text-align: center;">Anggaran</th>
                    <th style="text-align: center;">Realisasi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($result as $row)
                    <tr>
                        <td>{{ $row->tahun }}</td>
                        <td>{{ $row->kinerja }}</td>
                        <td style="text-align:right">{{ number_format($row->anggaran ?? 0, 2, ',', '.') }}</td>
                        <td style="text-align:right">{{ number_format($row->realisasi ?? 0, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <p>Belum ada data.</p>
@endif
@endsection
