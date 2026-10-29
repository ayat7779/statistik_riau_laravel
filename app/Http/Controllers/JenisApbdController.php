<?php

namespace App\Http\Controllers;

use App\Models\JenisApbd;
use Illuminate\Http\Request;

class JenisApbdController extends Controller
{
    public function index()
    {
        $jenisApdbs = JenisApbd::orderBy('id')->get();
        return view('jenis_apbd.index', [
            'pageTitle' => 'Jenis APBD',
            'jenisApdbs' => $jenisApdbs
        ]);
    }

    public function create()
    {
        return view('jenis_apbd.create', [
            'pageTitle' => 'Tambah Jenis APBD'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'uraian' => 'required|string|max:255',
            'akronim' => 'required|string|max:255',
        ]);

        JenisApbd::create($request->all());

        return redirect()->route('jenis-apbd.index')->with('success', 'Data Jenis APBD berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $jenisApbd = JenisApbd::findOrFail($id);
        return view('jenis_apbd.edit', [
            'pageTitle' => 'Edit Jenis APBD',
            'jenisApbd' => $jenisApbd
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'uraian' => 'required|string|max:255',
            'akronim' => 'required|string|max:255',
        ]);

        $jenisApbd = JenisApbd::findOrFail($id);
        $jenisApbd->update($request->all());

        return redirect()->route('jenis-apbd.index')->with('success', 'Data Jenis APBD berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $jenisApbd = JenisApbd::findOrFail($id);
        $jenisApbd->delete();

        return redirect()->route('jenis-apbd.index')->with('success', 'Data Jenis APBD berhasil dihapus.');
    }
}
