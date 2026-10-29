@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>Input Data APBD</h2>
    <a href="{{ route('input-apbd.create') }}" class="btn" style="background: #2563eb; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none;">+ Tambah Data APBD</a>
</div>

<!-- Year Filter Form -->
<form method="GET" action="" style="margin-bottom: 20px;">
    <label for="tahun" style="font-weight: 500; margin-right: 10px;">Filter Tahun:</label>
    <select name="tahun" id="tahun" onchange="this.form.submit()" style="padding: 6px 12px; border-radius: 4px; border: 1px solid #cbd5e1;">
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
                <th style="text-align: center;">Tahun</th>
                <th style="text-align: center;">Kode</th>
                <th style="text-align: center;">Nama Kode / Uraian</th>
                <th style="text-align: center;">Jumlah Anggaran</th>
                <th style="text-align: center;">Jumlah Perubahan</th>
                <th style="text-align: center;">Tahapan (Jenis APBD)</th>
                <th style="text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $row)
                <tr>
                    <td style="display: none;">{{ $row->id }}</td>
                    <td style="text-align: center;"><strong>{{ $row->tahun }}</strong></td>
                    <td>{{ $row->kodeUrut->no_urut ?? '-' }}</td>
                    <td>{{ $row->kodeUrut->uraian ?? '-' }}</td>
                    <td style="text-align:right">{{ number_format($row->jumlah_anggaran ?? 0, 2, ',', '.') }}</td>
                    <td style="text-align:right">{{ number_format($row->jumlah_perubahan ?? 0, 2, ',', '.') }}</td>
                    <td style="text-align: center;">{{ $row->jenisApbd->uraian ?? '-' }}</td>
                    <td style="text-align: center;">
                        <a href="{{ route('input-apbd.edit', $row->id) }}" style="color: #2563eb; margin-right: 10px; text-decoration: none;"><i class="fas fa-edit"></i> Edit</a>
                        <form action="{{ route('input-apbd.destroy', $row->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="color: #dc2626; background: none; border: none; cursor: pointer; padding: 0;"><i class="fas fa-trash"></i> Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">Belum ada data APBD.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
