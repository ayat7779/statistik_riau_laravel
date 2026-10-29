<?php

namespace App\Http\Controllers;

use App\Models\ApbdLampiran1;
use App\Models\KodeUrut;
use App\Models\JenisApbd;
use Illuminate\Http\Request;

class ApbdInputController extends Controller
{
    public function index(Request $request)
    {
        $available_years = ApbdLampiran1::distinct()->orderBy('tahun', 'desc')->pluck('tahun');

        $selected_year = $request->get('tahun', '');
        if ($selected_year === '' && $available_years->isNotEmpty()) {
            $selected_year = $available_years->first();
        }

        $query = ApbdLampiran1::with(['kodeUrut', 'jenisApbd']);

        if ($selected_year !== '') {
            $query->where('tahun', $selected_year);
        }

        $items = $query->orderBy('tahun', 'desc')->get();

        return view('apbd_input.index', [
            'pageTitle' => 'Input & Manajemen Data APBD',
            'items' => $items,
            'available_years' => $available_years,
            'selected_year' => $selected_year
        ]);
    }

    public function create()
    {
        $kodeUruts = KodeUrut::orderBy('no_urut')->get();
        $jenisApdbs = JenisApbd::all();

        return view('apbd_input.create', [
            'pageTitle' => 'Tambah Data APBD',
            'kodeUruts' => $kodeUruts,
            'jenisApdbs' => $jenisApdbs
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun' => 'required|integer',
            'id_kode_urut' => 'required|integer',
            'jumlah_anggaran' => 'required|numeric',
            'jumlah_perubahan' => 'required|numeric',
            'id_jenis_apbd' => 'required|integer',
        ]);

        ApbdLampiran1::create($request->all());

        return redirect()->route('input-apbd.index')->with('success', 'Data APBD berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $item = ApbdLampiran1::findOrFail($id);
        $kodeUruts = KodeUrut::orderBy('no_urut')->get();
        $jenisApdbs = JenisApbd::all();

        return view('apbd_input.edit', [
            'pageTitle' => 'Edit Data APBD',
            'item' => $item,
            'kodeUruts' => $kodeUruts,
            'jenisApdbs' => $jenisApdbs
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tahun' => 'required|integer',
            'id_kode_urut' => 'required|integer',
            'jumlah_anggaran' => 'required|numeric',
            'jumlah_perubahan' => 'required|numeric',
            'id_jenis_apbd' => 'required|integer',
        ]);

        $item = ApbdLampiran1::findOrFail($id);
        $item->update($request->all());

        return redirect()->route('input-apbd.index')->with('success', 'Data APBD berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = ApbdLampiran1::findOrFail($id);
        $item->delete();

        return redirect()->route('input-apbd.index')->with('success', 'Data APBD berhasil dihapus.');
    }
}
