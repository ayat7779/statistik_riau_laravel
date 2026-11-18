@extends('layouts.app')

@section('content')
<div style="max-width: 680px; margin: 0 auto; background: #ffffff; padding: 28px 32px; border-radius: 10px; border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 22px; padding-bottom: 14px; border-bottom: 1px solid #e2e8f0;">
        <div>
            <h2 style="margin: 0 0 4px 0; font-size: 20px; color: #0f172a;">
                <i class="fas fa-edit" style="color: #2563eb; margin-right: 8px;"></i> Edit Data Akun Aset
            </h2>
            <p style="margin: 0; color: #64748b; font-size: 13px;">Perbarui data akun aset: <strong>{{ $item->kode }} - {{ $item->nama_aset }}</strong></p>
        </div>
        <a href="{{ route('akun-aset.index') }}" style="color: #64748b; text-decoration: none; font-size: 13px; font-weight: 500; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    @if ($errors->any())
        <div style="background: #fee2e2; color: #991b1b; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; border-left: 4px solid #ef4444; font-size: 13.5px;">
            <div style="font-weight: 700; margin-bottom: 6px;"><i class="fas fa-exclamation-triangle"></i> Terjadi kesalahan input:</div>
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('akun-aset.update', $item->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 18px;">
            <label for="kode" style="display: block; margin-bottom: 6px; font-weight: 600; font-size: 13.5px; color: #334155;">
                Kode Akun Aset: <span style="color: #dc2626;">*</span>
            </label>
            <input type="text" name="kode" id="kode" value="{{ old('kode', $item->kode) }}" required style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px;">
            <small style="color: #64748b; font-size: 12px; display: block; margin-top: 4px;">Masukkan kode rekening atau klasifikasi aset sesuai standar.</small>
        </div>

        <div style="margin-bottom: 18px;">
            <label for="nama_aset" style="display: block; margin-bottom: 6px; font-weight: 600; font-size: 13.5px; color: #334155;">
                Nama Aset: <span style="color: #dc2626;">*</span>
            </label>
            <input type="text" name="nama_aset" id="nama_aset" value="{{ old('nama_aset', $item->nama_aset) }}" required style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px;">
        </div>

        <div style="margin-bottom: 18px;">
            <label for="kelompok" style="display: block; margin-bottom: 6px; font-weight: 600; font-size: 13.5px; color: #334155;">
                Kelompok (Klasifikasi Level): <span style="color: #dc2626;">*</span>
            </label>
            <select name="kelompok" id="kelompok" required style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; background: #ffffff;">
                <option value="">-- Pilih Kelompok --</option>
                @foreach($daftarKelompok as $k)
                    <option value="{{ $k }}" {{ old('kelompok', $item->kelompok) == $k ? 'selected' : '' }}>
                        {{ ucwords($k) }}
                    </option>
                @endforeach
            </select>
            <small style="color: #64748b; font-size: 12px; display: block; margin-top: 4px;">Pilih jenjang tingkatan: Akun, Kelompok, Jenis, Objek, Rincian Objek, atau Sub Rincian Objek.</small>
        </div>

        <div style="margin-bottom: 24px;">
            <label for="akronim" style="display: block; margin-bottom: 6px; font-weight: 600; font-size: 13.5px; color: #334155;">
                Akronim:
            </label>
            <input type="text" name="akronim" id="akronim" value="{{ old('akronim', $item->akronim) }}" style="width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px;">
            <small style="color: #64748b; font-size: 12px; display: block; margin-top: 4px;">Opsional. Singkatan nama aset untuk referensi cepat.</small>
        </div>

        <div style="display: flex; align-items: center; gap: 12px; padding-top: 10px; border-top: 1px solid #e2e8f0;">
            <button type="submit" style="background: #2563eb; color: white; padding: 10px 24px; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 14px; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-save"></i> Perbarui Data
            </button>
            <a href="{{ route('akun-aset.index') }}" style="color: #64748b; text-decoration: none; padding: 10px 18px; font-size: 14px; font-weight: 500;">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
