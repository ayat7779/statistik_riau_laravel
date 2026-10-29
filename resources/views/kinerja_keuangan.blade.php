@extends('layouts.app')

@section('content')
<h2 style="text-align: center;">KINERJA KEUANGAN PROVINSI RIAU</h2>

<form method="GET" action="" style="margin-bottom: 20px; display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
    <div>
        <label for="tahun" style="font-weight: 500; margin-right: 5px;">Filter Tahun:</label>
        <select name="tahun" id="tahun" onchange="this.form.submit()" style="padding: 6px 12px; border-radius: 4px; border: 1px solid #cbd5e1;">
            <option value="">-- Semua Tahun --</option>
            @foreach ($available_years as $year)
                <option value="{{ $year }}" {{ $selected_year == $year ? 'selected' : '' }}>
                    {{ $year }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="kinerja" style="font-weight: 500; margin-right: 5px;">Filter Kinerja:</label>
        <select name="kinerja" id="kinerja" onchange="this.form.submit()" style="padding: 6px 12px; border-radius: 4px; border: 1px solid #cbd5e1;">
            <option value="">-- Semua Kinerja --</option>
            @foreach ($available_kinerja as $kinerja_item)
                <option value="{{ $kinerja_item }}" {{ $selected_kinerja == $kinerja_item ? 'selected' : '' }}>
                    {{ $kinerja_item }}
                </option>
            @endforeach
        </select>
    </div>

    <input type="hidden" name="page" value="kinerja_keuangan">
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
                    <th style="text-align: center;">Tahun</th>
                    <th style="text-align: center;">Kinerja</th>
                    <th style="text-align: center;">Target</th>
                    <th style="text-align: center;">Realisasi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($result as $row)
                    <tr>
                        <td style="text-align: center;">{{ $row->tahun }}</td>
                        <td>{{ $row->kinerja }}</td>
                        <td style="text-align:right">{{ number_format($row->anggaran ?? 0, 2, ',', '.') }}</td>
                        <td style="text-align:right">{{ number_format($row->realisasi ?? 0, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <p>Belum ada data untuk kriteria yang dipilih.</p>
@endif
@endsection
