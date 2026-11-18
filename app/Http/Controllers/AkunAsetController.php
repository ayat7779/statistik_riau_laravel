<?php

namespace App\Http\Controllers;

use App\Models\AkunAset;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AkunAsetController extends Controller
{
    public function index(Request $request)
    {
        $selected_kelompok = $request->get('kelompok', '');
        $search = $request->get('search', '');

        $query = AkunAset::query();

        if ($selected_kelompok !== '') {
            $query->where('kelompok', $selected_kelompok);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('kode', 'ilike', "%{$search}%")
                  ->orWhere('nama_aset', 'ilike', "%{$search}%")
                  ->orWhere('akronim', 'ilike', "%{$search}%");
            });
        }

        $items = $query->orderBy('kode')->get();

        return view('akun_aset.index', [
            'pageTitle' => 'Input & Manajemen Data Akun Aset',
            'items' => $items,
            'daftarKelompok' => AkunAset::DAFTAR_KELOMPOK,
            'selected_kelompok' => $selected_kelompok,
            'search' => $search,
        ]);
    }

    public function create()
    {
        return view('akun_aset.create', [
            'pageTitle' => 'Tambah Data Akun Aset',
            'daftarKelompok' => AkunAset::DAFTAR_KELOMPOK,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:100',
            'nama_aset' => 'required|string|max:255',
            'akronim' => 'nullable|string|max:100',
            'kelompok' => ['required', Rule::in(AkunAset::DAFTAR_KELOMPOK)],
        ]);

        AkunAset::create($validated);

        return redirect()->route('akun-aset.index')->with('success', 'Data Akun Aset berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $item = AkunAset::findOrFail($id);

        return view('akun_aset.edit', [
            'pageTitle' => 'Edit Data Akun Aset',
            'item' => $item,
            'daftarKelompok' => AkunAset::DAFTAR_KELOMPOK,
        ]);
    }

    public function update(Request $request, $id)
    {
        $item = AkunAset::findOrFail($id);

        $validated = $request->validate([
            'kode' => 'required|string|max:100',
            'nama_aset' => 'required|string|max:255',
            'akronim' => 'nullable|string|max:100',
            'kelompok' => ['required', Rule::in(AkunAset::DAFTAR_KELOMPOK)],
        ]);

        $item->update($validated);

        return redirect()->route('akun-aset.index')->with('success', 'Data Akun Aset berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = AkunAset::findOrFail($id);
        $item->delete();

        return redirect()->route('akun-aset.index')->with('success', 'Data Akun Aset berhasil dihapus.');
    }
}
