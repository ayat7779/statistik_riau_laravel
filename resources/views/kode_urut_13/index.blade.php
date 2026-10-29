@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>Kode Urut PMDN13 APBD</h2>
    <a href="{{ route('kode-urut-13.create') }}" class="btn" style="background: #2563eb; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none;">+ Tambah Kode Urut PMDN13</a>
</div>

@if(session('success'))
    <div class="message-box message-success" style="padding: 10px; background: #dcfce7; color: #166534; margin-bottom: 15px; border-radius: 4px;">
        {{ session('success') }}
    </div>
@endif

<div>
    <table>
        <thead>
            <tr>
                <th style="text-align: center;">No</th>
                <th style="text-align: center;">No Urut</th>
                <th style="text-align: center;">Uraian</th>
                <th style="text-align: center;">Akronim</th>
                <th style="text-align: center;">Level</th>
                <th style="text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kodeUruts as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td><strong>{{ $item->no_urut }}</strong></td>
                    <td>{{ $item->uraian }}</td>
                    <td>{{ $item->akronim ?? '-' }}</td>
                    <td>{{ $item->kodeLevel->nama_level ?? '-' }}</td>
                    <td style="text-align: center;">
                        <a href="{{ route('kode-urut-13.edit', $item->id) }}" style="color: #2563eb; margin-right: 10px; text-decoration: none;"><i class="fas fa-edit"></i> Edit</a>
                        <form action="{{ route('kode-urut-13.destroy', $item->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="color: #dc2626; background: none; border: none; cursor: pointer; padding: 0;"><i class="fas fa-trash"></i> Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Belum ada data kode urut PMDN13.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
