<?php

namespace App\Http\Controllers;

use App\Models\KodeLevel;
use Illuminate\Http\Request;

class KodeLevelController extends Controller
{
    public function index()
    {
        $kodeLevels = KodeLevel::orderBy('id')->get();
        return view('kode_level.index', [
            'pageTitle' => 'Level Kode',
            'kodeLevels' => $kodeLevels
        ]);
    }

    public function create()
    {
        return view('kode_level.create', [
            'pageTitle' => 'Tambah Level Kode'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_level' => 'required|string|max:255',
            'akronim' => 'nullable|string|max:255',
        ]);

        KodeLevel::create($request->all());

        return redirect()->route('level-kode.index')->with('success', 'Data Level Kode berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kodeLevel = KodeLevel::findOrFail($id);
        return view('kode_level.edit', [
            'pageTitle' => 'Edit Level Kode',
            'kodeLevel' => $kodeLevel
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_level' => 'required|string|max:255',
            'akronim' => 'nullable|string|max:255',
        ]);

        $kodeLevel = KodeLevel::findOrFail($id);
        $kodeLevel->update($request->all());

        return redirect()->route('level-kode.index')->with('success', 'Data Level Kode berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kodeLevel = KodeLevel::findOrFail($id);
        $kodeLevel->delete();

        return redirect()->route('level-kode.index')->with('success', 'Data Level Kode berhasil dihapus.');
    }
}
