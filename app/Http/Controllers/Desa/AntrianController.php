<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\Antrian;
use App\Models\JenisSurat;
use App\Models\PermohonanSurat;
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

    public function update(Request $request, Antrian $antrian)
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

        $antrian->update($attr);

        Alert::success('Success', 'Antrian berhasil diupdate');
        return redirect()->route('desa.antrian.index');
    }

    public function destroy(Antrian $antrian)
    {
        $antrian->delete();
        Alert::success('Success', 'Antrian berhasil dihapus');
        return redirect()->route('desa.antrian.index');
    }

    public function status(Antrian $antrian)
    {
        if (request('status') == 1) {
            $txt = 'Nomor Antrian ' . $antrian->no_antrian;
            $txt = rawurlencode($txt);
            $html = file_get_contents('https://translate.google.com/translate_tts?ie=UTF-8&client=gtx&q=' . $txt . '&tl=id-IN');
            $player = "<audio hidden controls='controls' autoplay><source src='data:audio/mpeg;base64," . base64_encode($html) . "'></audio>";

            $antrian->update(['status' => 1]);

            return back()->with('speech', $player);
        } else {
            $antrian->update(['status' => 2]);
            PermohonanSurat::create([
                'jenis_surat_id' => $antrian->jenis_surat_id,
                'warga_id' => $antrian->warga_id,
                'desa_id' => $antrian->desa_id,
            ]);

            Alert::success('Success', 'Antrian berhasil dikonfirmasi');
            return back();
        }
    }
}
