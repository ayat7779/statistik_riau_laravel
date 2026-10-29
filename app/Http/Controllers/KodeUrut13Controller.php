<?php

namespace App\Http\Controllers;

use App\Models\KodeUrut13;
use App\Models\KodeLevel;
use Illuminate\Http\Request;

class KodeUrut13Controller extends Controller
{
    public function index()
    {
        $kodeUruts = KodeUrut13::with('kodeLevel')->orderBy('no_urut')->get();
        return view('kode_urut_13.index', [
            'pageTitle' => 'Kode Urut PMDN13 APBD',
            'kodeUruts' => $kodeUruts
        ]);
    }

    public function create()
    {
        $levels = KodeLevel::all();
        return view('kode_urut_13.create', [
            'pageTitle' => 'Tambah Kode Urut PMDN13',
            'levels' => $levels
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_urut' => 'required|string|max:11',
            'uraian' => 'required|string|max:255',
            'akronim' => 'nullable|string|max:255',
            'id_kode_level' => 'nullable|integer',
        ]);

        KodeUrut13::create($request->all());

        return redirect()->route('kode-urut-13.index')->with('success', 'Data Kode Urut PMDN13 berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kodeUrut = KodeUrut13::findOrFail($id);
        $levels = KodeLevel::all();
        return view('kode_urut_13.edit', [
            'pageTitle' => 'Edit Kode Urut PMDN13',
            'kodeUrut' => $kodeUrut,
            'levels' => $levels
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'no_urut' => 'required|string|max:11',
            'uraian' => 'required|string|max:255',
            'akronim' => 'nullable|string|max:255',
            'id_kode_level' => 'nullable|integer',
        ]);

        $kodeUrut = KodeUrut13::findOrFail($id);
        $kodeUrut->update($request->all());

        return redirect()->route('kode-urut-13.index')->with('success', 'Data Kode Urut PMDN13 berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kodeUrut = KodeUrut13::findOrFail($id);
        $kodeUrut->delete();

        return redirect()->route('kode-urut-13.index')->with('success', 'Data Kode Urut PMDN13 berhasil dihapus.');
    }
}
