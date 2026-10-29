@extends('layouts.app')

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <h2>Tambah Data Lampiran I - APBD</h2>

    @if ($errors->any())
        <div style="background: #fee2e2; color: #991b1b; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('apbd-lampiran-1.store') }}" method="POST">
        @csrf
        <div style="margin-bottom: 15px;">
            <label for="tahun" style="display: block; margin-bottom: 5px; font-weight: 500;">Tahun:</label>
            <input type="number" name="tahun" id="tahun" value="{{ old('tahun', date('Y')) }}" required style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 4px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label for="id_kode_urut" style="display: block; margin-bottom: 5px; font-weight: 500;">Kode Urut:</label>
            <select name="id_kode_urut" id="id_kode_urut" required style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 4px;">
                <option value="">-- Pilih Kode Urut --</option>
                @foreach($kodeUruts as $ku)
                    <option value="{{ $ku->id }}" {{ old('id_kode_urut') == $ku->id ? 'selected' : '' }}>
                        {{ $ku->no_urut }} - {{ $ku->uraian }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <label for="jumlah_anggaran" style="display: block; margin-bottom: 5px; font-weight: 500;">Jumlah Anggaran:</label>
            <input type="number" step="0.01" name="jumlah_anggaran" id="jumlah_anggaran" value="{{ old('jumlah_anggaran') }}" required style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 4px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label for="jumlah_perubahan" style="display: block; margin-bottom: 5px; font-weight: 500;">Jumlah Perubahan:</label>
            <input type="number" step="0.01" name="jumlah_perubahan" id="jumlah_perubahan" value="{{ old('jumlah_perubahan') }}" required style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 4px;">
        </div>

        <div style="margin-bottom: 20px;">
            <label for="id_jenis_apbd" style="display: block; margin-bottom: 5px; font-weight: 500;">Jenis APBD (Tahapan):</label>
            <select name="id_jenis_apbd" id="id_jenis_apbd" required style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 4px;">
                <option value="">-- Pilih Jenis APBD --</option>
                @foreach($jenisApdbs as $ja)
                    <option value="{{ $ja->id }}" {{ old('id_jenis_apbd') == $ja->id ? 'selected' : '' }}>
                        {{ $ja->uraian }} ({{ $ja->akronim }})
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <button type="submit" style="background: #2563eb; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">Simpan</button>
            <a href="{{ route('apbd-lampiran-1.index') }}" style="margin-left: 10px; color: #64748b; text-decoration: none;">Batal</a>
        </div>
    </form>
</div>
@endsection
