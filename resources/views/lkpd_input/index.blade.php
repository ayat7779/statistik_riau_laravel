@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>Input Data LKPD</h2>
    <a href="{{ route('input-lkpd.create') }}" class="btn" style="background: #2563eb; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none;">+ Tambah Data LKPD</a>
</div>

<!-- Year Filter Form -->
<form method="GET" action="" style="margin-bottom: 20px;">
    <label for="tahun_lkpd" style="font-weight: 500; margin-right: 10px;">Filter Tahun LKPD:</label>
    <select name="tahun_lkpd" id="tahun_lkpd" onchange="this.form.submit()" style="padding: 6px 12px; border-radius: 4px; border: 1px solid #cbd5e1;">
        <option value="">-- Semua Tahun --</option>
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
                <th style="display: none;">ID</th>
                <th style="text-align: center;">Tahun LKPD</th>
                <th style="text-align: center;">Kode Catatan</th>
                <th style="text-align: center;">Uraian Catatan</th>
                <th style="text-align: center;">Jumlah Anggaran</th>
                <th style="text-align: center;">Jumlah Realisasi</th>
                <th style="text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $row)
                <tr>
                    <td style="display: none;">{{ $row->id }}</td>
                    <td style="text-align: center;"><strong>{{ $row->tahun_lkpd }}</strong></td>
                    <td>{{ $row->kodeCatatan->kode_catatan ?? '-' }}</td>
                    <td>{{ $row->kodeCatatan->uraian ?? '-' }}</td>
                    <td style="text-align:right">{{ number_format($row->jumlah_anggaran ?? 0, 2, ',', '.') }}</td>
                    <td style="text-align:right">{{ number_format($row->jumlah_realisasi ?? 0, 2, ',', '.') }}</td>
                    <td style="text-align: center;">
                        <a href="{{ route('input-lkpd.edit', $row->id) }}" style="color: #2563eb; margin-right: 10px; text-decoration: none;"><i class="fas fa-edit"></i> Edit</a>
                        <form action="{{ route('input-lkpd.destroy', $row->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="color: #dc2626; background: none; border: none; cursor: pointer; padding: 0;"><i class="fas fa-trash"></i> Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">Belum ada data LKPD.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
