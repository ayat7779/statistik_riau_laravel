@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
    <div>
        <h2 style="margin: 0 0 4px 0;">Input & Manajemen Data Akun Aset</h2>
        <p style="margin: 0; color: #64748b; font-size: 13.5px;">Kelola daftar akun aset daerah beserta klasifikasi kelompok dan nilai realisasinya</p>
    </div>
    <a href="{{ route('akun-aset.create') }}" class="btn" style="background: #2563eb; color: white; padding: 9px 18px; border-radius: 6px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 2px 4px rgba(37,99,235,0.2);">
        <i class="fas fa-plus"></i> Tambah Akun Aset
    </a>
</div>

<!-- Filter & Search Form -->
<form method="GET" action="" style="margin-bottom: 20px; display: flex; flex-wrap: wrap; gap: 12px; align-items: center; background: #ffffff; padding: 14px 18px; border-radius: 8px; border: 1px solid #e2e8f0;">
    <div style="display: flex; align-items: center; gap: 8px;">
        <label for="kelompok" style="font-weight: 600; font-size: 13.5px; color: #334155;">Kelompok:</label>
        <select name="kelompok" id="kelompok" onchange="this.form.submit()" style="padding: 7px 12px; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 13.5px; background: #f8fafc;">
            <option value="">-- Semua Kelompok --</option>
            @foreach ($daftarKelompok as $k)
                <option value="{{ $k }}" {{ $selected_kelompok == $k ? 'selected' : '' }}>
                    {{ ucwords($k) }}
                </option>
            @endforeach
        </select>
    </div>

    <div style="display: flex; align-items: center; gap: 8px; flex: 1; min-width: 240px;">
        <label for="search" style="font-weight: 600; font-size: 13.5px; color: #334155;">Cari:</label>
        <input type="text" name="search" id="search" value="{{ $search }}" placeholder="Cari Kode, Nama Aset, Akronim..." style="width: 100%; padding: 7px 12px; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 13.5px;">
    </div>

    <button type="submit" style="background: #0f172a; color: white; border: none; padding: 7px 16px; border-radius: 6px; font-weight: 600; font-size: 13.5px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
        <i class="fas fa-search"></i> Cari
    </button>
    @if($selected_kelompok !== '' || $search !== '')
        <a href="{{ route('akun-aset.index') }}" style="color: #64748b; font-size: 13px; text-decoration: none; padding: 7px 10px;">
            <i class="fas fa-undo"></i> Reset
        </a>
    @endif
</form>

@if(session('success'))
    <div class="message-box message-success" style="padding: 12px 16px; background: #dcfce7; color: #166534; margin-bottom: 20px; border-radius: 6px; border-left: 4px solid #22c55e;">
        <i class="fas fa-check-circle" style="margin-right: 6px;"></i> {{ session('success') }}
    </div>
@endif

<div style="background: #ffffff; border-radius: 8px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.04);">
    <table style="width: 100%; border-collapse: collapse; margin-top: 0;">
        <thead>
            <tr style="background: #1e293b; color: #ffffff;">
                <th style="text-align: center; width: 60px;">No</th>
                <th style="text-align: left; width: 180px;">Kode</th>
                <th style="text-align: left;">Nama Aset</th>
                <th style="text-align: center; width: 140px;">Akronim</th>
                <th style="text-align: center; width: 180px;">Kelompok</th>
                <th style="text-align: center; width: 150px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $index => $row)
                @php
                    $badgeColors = [
                        'akun' => ['bg' => '#dbeafe', 'text' => '#1e40af'],
                        'kelompok' => ['bg' => '#fef3c7', 'text' => '#92400e'],
                        'jenis' => ['bg' => '#e0e7ff', 'text' => '#3730a3'],
                        'objek' => ['bg' => '#d1fae5', 'text' => '#065f46'],
                        'rincian objek' => ['bg' => '#fce7f3', 'text' => '#9d174d'],
                        'sub rincian objek' => ['bg' => '#ede9fe', 'text' => '#5b21b6'],
                    ];
                    $badge = $badgeColors[$row->kelompok] ?? ['bg' => '#f1f5f9', 'text' => '#475569'];
                @endphp
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="text-align: center; color: #64748b;">{{ $index + 1 }}</td>
                    <td style="font-weight: 700; color: #0f172a; font-family: monospace; font-size: 14px;">{{ $row->kode }}</td>
                    <td style="font-weight: 500; color: #1e293b;">{{ $row->nama_aset }}</td>
                    <td style="text-align: center; color: #475569;">{{ $row->akronim ?? '-' }}</td>
                    <td style="text-align: center;">
                        <span style="display: inline-block; padding: 3px 10px; border-radius: 9999px; font-size: 11.5px; font-weight: 600; background: {{ $badge['bg'] }}; color: {{ $badge['text'] }}; text-transform: uppercase;">
                            {{ $row->kelompok }}
                        </span>
                    </td>
                    <td style="text-align: center;">
                        <a href="{{ route('akun-aset.edit', $row->id) }}" style="color: #2563eb; margin-right: 12px; text-decoration: none; font-weight: 600; font-size: 13px;">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('akun-aset.destroy', $row->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data akun aset ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="color: #dc2626; background: none; border: none; cursor: pointer; padding: 0; font-weight: 600; font-size: 13px;">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 30px; color: #64748b;">
                        <i class="fas fa-inbox" style="font-size: 32px; display: block; margin-bottom: 8px; opacity: 0.5;"></i>
                        Belum ada data akun aset. Silakan klik tombol <strong>"+ Tambah Akun Aset"</strong> untuk menginput data baru.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
