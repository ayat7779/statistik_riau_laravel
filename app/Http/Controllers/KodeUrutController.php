<?php

namespace App\Http\Controllers;

use App\Models\KodeUrut;
use App\Models\KodeLevel;
use Illuminate\Http\Request;

class KodeUrutController extends Controller
{
    public function index()
    {
        $kodeUruts = KodeUrut::with('kodeLevel')->orderBy('no_urut')->get();
        return view('kode_urut.index', [
            'pageTitle' => 'Kode Urut PMDN77 APBD',
            'kodeUruts' => $kodeUruts
        ]);
    }

    public function create()
    {
        $levels = KodeLevel::all();
        return view('kode_urut.create', [
            'pageTitle' => 'Tambah Kode Urut',
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

        KodeUrut::create($request->all());

        return redirect()->route('kode-urut.index')->with('success', 'Data Kode Urut berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kodeUrut = KodeUrut::findOrFail($id);
        $levels = KodeLevel::all();
        return view('kode_urut.edit', [
            'pageTitle' => 'Edit Kode Urut',
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

        $kodeUrut = KodeUrut::findOrFail($id);
        $kodeUrut->update($request->all());

        return redirect()->route('kode-urut.index')->with('success', 'Data Kode Urut berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kodeUrut = KodeUrut::findOrFail($id);
        $kodeUrut->delete();

        return redirect()->route('kode-urut.index')->with('success', 'Data Kode Urut berhasil dihapus.');
    }
}
