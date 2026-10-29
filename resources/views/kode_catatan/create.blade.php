@extends('layouts.app')

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <h2>Tambah Kode Catatan Baru</h2>

    @if ($errors->any())
        <div style="background: #fee2e2; color: #991b1b; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('kode-catatan.store') }}" method="POST">
        @csrf
        <div style="margin-bottom: 15px;">
            <label for="kode_catatan" style="display: block; margin-bottom: 5px; font-weight: 500;">Kode Catatan:</label>
            <input type="text" name="kode_catatan" id="kode_catatan" value="{{ old('kode_catatan') }}" required style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 4px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label for="uraian" style="display: block; margin-bottom: 5px; font-weight: 500;">Uraian:</label>
            <textarea name="uraian" id="uraian" rows="4" required style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 4px;">{{ old('uraian') }}</textarea>
        </div>

        <div style="margin-bottom: 20px;">
            <label for="id_kode_level" style="display: block; margin-bottom: 5px; font-weight: 500;">Level Kode:</label>
            <select name="id_kode_level" id="id_kode_level" style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 4px;">
                <option value="">-- Pilih Level Kode --</option>
                @foreach($levels as $level)
                    <option value="{{ $level->id }}" {{ old('id_kode_level') == $level->id ? 'selected' : '' }}>
                        {{ $level->id }} - {{ $level->nama_level }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <button type="submit" style="background: #2563eb; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">Simpan</button>
            <a href="{{ route('kode-catatan.index') }}" style="margin-left: 10px; color: #64748b; text-decoration: none;">Batal</a>
        </div>
    </form>
</div>
@endsection
