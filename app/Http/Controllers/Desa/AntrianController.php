<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\Antrian;
use App\Models\JenisSurat;
use App\Models\Warga;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class AntrianController extends Controller
{
    public function index()
    {
        $antrians = Antrian::get();

        return view('desa.antrian.index', compact('antrians'));
    }

    public function create()
    {
        $no_antri = Antrian::where('tanggal_antri', date('Y-m-d'))->where('status', 0)->count();
        $warga = Warga::where('desa_id', auth()->user()->desa[0]->id)->get();
        $jenis = JenisSurat::get();
        $antrian = new Antrian();

        return view('desa.antrian.create', compact('warga', 'no_antri', 'jenis', 'antrian'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_antrian' => 'required',
            'warga' => 'required',
            'jenis' => 'required',
        ]);
        $attr = $request->except('jenis', 'warga');

        $attr['warga_id'] = $request->warga;
        $attr['jenis_surat_id'] = $request->jenis;
        $attr['desa_id'] = auth()->user()->desa[0]->id;
        $attr['tanggal_antri'] = now('Asia/Jakarta');

        Antrian::create($attr);

        Alert::success('Success', 'Antrian berhasil dibuat');
        return redirect()->route('desa.antrian.index');
    }

    public function show(Antrian $antrian)
    {
        //
    }

    public function edit(Antrian $antrian)
    {
        $warga = Warga::where('desa_id', auth()->user()->desa[0]->id)->get();
        $jenis = JenisSurat::get();

        return view('desa.antrian.edit', compact('warga', 'jenis', 'antrian'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Antrian  $antrian
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Antrian $antrian)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Antrian  $antrian
     * @return \Illuminate\Http\Response
     */
    public function destroy(Antrian $antrian)
    {
        //
    }
}
