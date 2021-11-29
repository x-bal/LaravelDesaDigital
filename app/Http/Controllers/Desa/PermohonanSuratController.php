<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\JenisSurat;
use App\Models\PermohonanSurat;
use App\Models\Warga;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class PermohonanSuratController extends Controller
{
    public function index()
    {
        $permohonans = PermohonanSurat::where('desa_id', auth()->user()->desa[0]->id)->get();

        return view('desa.permohonan.index', compact('permohonans'));
    }

    public function create()
    {
        $warga = Warga::where('desa_id', auth()->user()->desa[0]->id)->get();
        $jenis = JenisSurat::get();
        $permohonanSurat = new PermohonanSurat();

        return view('desa.permohonan.create', compact('warga', 'jenis', 'permohonanSurat'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'warga' => 'required',
            'jenis' => 'required',
        ]);

        PermohonanSurat::create([
            'warga_id' => $request->warga,
            'jenis_surat_id' => $request->jenis,
            'desa_id' => auth()->user()->desa[0]->id
        ]);

        Alert::success('Success', 'Permohonan Surat berhasil dibuat');
        return redirect()->route('desa.permohonan.index');
    }

    public function show(PermohonanSurat $permohonanSurat)
    {
        //
    }

    public function edit($id)
    {
        $permohonanSurat = PermohonanSurat::findorFail($id);
        $warga = Warga::where('desa_id', auth()->user()->desa[0]->id)->get();
        $jenis = JenisSurat::get();

        return view('desa.permohonan.edit', compact('warga', 'jenis', 'permohonanSurat'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'warga' => 'required',
            'jenis' => 'required',
        ]);

        $permohonanSurat = PermohonanSurat::findOrFail($id);

        $permohonanSurat->update([
            'warga_id' => $request->warga,
            'jenis_surat_id' => $request->jenis,
        ]);

        Alert::success('Success', 'Permohonan Surat berhasil diupdate');
        return redirect()->route('desa.permohonan.index');
    }

    public function destroy($id)
    {
        $permohonanSurat = PermohonanSurat::findOrFail($id);
        $permohonanSurat->delete();

        Alert::success('Success', 'Permohonan Surat berhasil didelete');
        return redirect()->route('desa.permohonan.index');
    }
}
