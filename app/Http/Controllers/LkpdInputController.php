<?php

namespace App\Http\Controllers;

use App\Models\LkpdApbdLampiran1;
use App\Models\KodeCatatan;
use Illuminate\Http\Request;

class LkpdInputController extends Controller
{
    public function index(Request $request)
    {
        $available_years = LkpdApbdLampiran1::distinct()->orderBy('tahun_lkpd', 'desc')->pluck('tahun_lkpd');

        $selected_year = $request->get('tahun_lkpd', '');
        if ($selected_year === '' && $available_years->isNotEmpty()) {
            $selected_year = $available_years->first();
        }

        $query = LkpdApbdLampiran1::with(['kodeCatatan']);

        if ($selected_year !== '') {
            $query->where('tahun_lkpd', $selected_year);
        }

        $items = $query->orderBy('tahun_lkpd', 'desc')->get();

        return view('lkpd_input.index', [
            'pageTitle' => 'Input & Manajemen Data LKPD',
            'items' => $items,
            'available_years' => $available_years,
            'selected_year' => $selected_year
        ]);
    }

    public function create()
    {
        $kodeCatatans = KodeCatatan::orderBy('kode_catatan')->get();

        return view('lkpd_input.create', [
            'pageTitle' => 'Tambah Data LKPD',
            'kodeCatatans' => $kodeCatatans
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun_lkpd' => 'required|integer',
            'id_kode_catatan' => 'required|integer',
            'jumlah_anggaran' => 'required|numeric',
            'jumlah_realisasi' => 'required|numeric',
        ]);

        LkpdApbdLampiran1::create($request->all());

        return redirect()->route('input-lkpd.index')->with('success', 'Data LKPD berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $item = LkpdApbdLampiran1::findOrFail($id);
        $kodeCatatans = KodeCatatan::orderBy('kode_catatan')->get();

        return view('lkpd_input.edit', [
            'pageTitle' => 'Edit Data LKPD',
            'item' => $item,
            'kodeCatatans' => $kodeCatatans
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tahun_lkpd' => 'required|integer',
            'id_kode_catatan' => 'required|integer',
            'jumlah_anggaran' => 'required|numeric',
            'jumlah_realisasi' => 'required|numeric',
        ]);

        $item = LkpdApbdLampiran1::findOrFail($id);
        $item->update($request->all());

        return redirect()->route('input-lkpd.index')->with('success', 'Data LKPD berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = LkpdApbdLampiran1::findOrFail($id);
        $item->delete();

        return redirect()->route('input-lkpd.index')->with('success', 'Data LKPD berhasil dihapus.');
    }
}
