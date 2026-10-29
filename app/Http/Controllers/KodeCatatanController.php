<?php

namespace App\Http\Controllers;

use App\Models\KodeCatatan;
use App\Models\KodeLevel;
use Illuminate\Http\Request;

class KodeCatatanController extends Controller
{
    public function index()
    {
        $kodeCatatans = KodeCatatan::with('kodeLevel')->orderBy('kode_catatan')->get();
        return view('kode_catatan.index', [
            'pageTitle' => 'Kode Catatan LKPD APBD',
            'kodeCatatans' => $kodeCatatans
        ]);
    }

    public function create()
    {
        $levels = KodeLevel::all();
        return view('kode_catatan.create', [
            'pageTitle' => 'Tambah Kode Catatan',
            'levels' => $levels
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_catatan' => 'required|string|max:255',
            'uraian' => 'required|string',
            'id_kode_level' => 'nullable|integer',
        ]);

        KodeCatatan::create($request->all());

        return redirect()->route('kode-catatan.index')->with('success', 'Data Kode Catatan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kodeCatatan = KodeCatatan::findOrFail($id);
        $levels = KodeLevel::all();
        return view('kode_catatan.edit', [
            'pageTitle' => 'Edit Kode Catatan',
            'kodeCatatan' => $kodeCatatan,
            'levels' => $levels
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_catatan' => 'required|string|max:255',
            'uraian' => 'required|string',
            'id_kode_level' => 'nullable|integer',
        ]);

        $kodeCatatan = KodeCatatan::findOrFail($id);
        $kodeCatatan->update($request->all());

        return redirect()->route('kode-catatan.index')->with('success', 'Data Kode Catatan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kodeCatatan = KodeCatatan::findOrFail($id);
        $kodeCatatan->delete();

        return redirect()->route('kode-catatan.index')->with('success', 'Data Kode Catatan berhasil dihapus.');
    }
}
