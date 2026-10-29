@extends('layouts.app')

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <h2>Tambah Data LKPD</h2>

    @if ($errors->any())
        <div style="background: #fee2e2; color: #991b1b; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('input-lkpd.store') }}" method="POST">
        @csrf
        <div style="margin-bottom: 15px;">
            <label for="tahun_lkpd" style="display: block; margin-bottom: 5px; font-weight: 500;">Tahun LKPD:</label>
            <input type="number" name="tahun_lkpd" id="tahun_lkpd" value="{{ old('tahun_lkpd', date('Y')) }}" required style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 4px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label for="id_kode_catatan" style="display: block; margin-bottom: 5px; font-weight: 500;">Kode Catatan:</label>
            <select name="id_kode_catatan" id="id_kode_catatan" required style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 4px;">
                <option value="">-- Pilih Kode Catatan --</option>
                @foreach($kodeCatatans as $kc)
                    <option value="{{ $kc->id }}" {{ old('id_kode_catatan') == $kc->id ? 'selected' : '' }}>
                        {{ $kc->kode_catatan }} - {{ $kc->uraian }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <label for="jumlah_anggaran" style="display: block; margin-bottom: 5px; font-weight: 500;">Jumlah Anggaran:</label>
            <input type="number" step="0.01" name="jumlah_anggaran" id="jumlah_anggaran" value="{{ old('jumlah_anggaran') }}" required style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 4px;">
        </div>

        <div style="margin-bottom: 20px;">
            <label for="jumlah_realisasi" style="display: block; margin-bottom: 5px; font-weight: 500;">Jumlah Realisasi:</label>
            <input type="number" step="0.01" name="jumlah_realisasi" id="jumlah_realisasi" value="{{ old('jumlah_realisasi') }}" required style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 4px;">
        </div>

        <div>
            <button type="submit" style="background: #2563eb; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">Simpan</button>
            <a href="{{ route('input-lkpd.index') }}" style="margin-left: 10px; color: #64748b; text-decoration: none;">Batal</a>
        </div>
    </form>
</div>
@endsection
