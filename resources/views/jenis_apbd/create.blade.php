@extends('layouts.app')

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <h2>Tambah Jenis APBD Baru</h2>

    @if ($errors->any())
        <div style="background: #fee2e2; color: #991b1b; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('jenis-apbd.store') }}" method="POST">
        @csrf
        <div style="margin-bottom: 15px;">
            <label for="uraian" style="display: block; margin-bottom: 5px; font-weight: 500;">Uraian:</label>
            <input type="text" name="uraian" id="uraian" value="{{ old('uraian') }}" required style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 4px;">
        </div>

        <div style="margin-bottom: 20px;">
            <label for="akronim" style="display: block; margin-bottom: 5px; font-weight: 500;">Akronim:</label>
            <input type="text" name="akronim" id="akronim" value="{{ old('akronim') }}" required style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 4px;">
        </div>

        <div>
            <button type="submit" style="background: #2563eb; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">Simpan</button>
            <a href="{{ route('jenis-apbd.index') }}" style="margin-left: 10px; color: #64748b; text-decoration: none;">Batal</a>
        </div>
    </form>
</div>
@endsection
