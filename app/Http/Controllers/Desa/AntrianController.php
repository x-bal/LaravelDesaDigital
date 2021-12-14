<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\Antrian;
use App\Models\JenisSurat;
use App\Models\Loket;
use App\Models\PermohonanSurat;
use App\Models\Warga;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class AntrianController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->from;
        $to = $request->to;

        if ($from && $to) {
            $antrians = Antrian::where('desa_id', auth()->user()->desa_id)->whereBetween('tanggal_antri', [$from, $to])->get();
        } else {
            $antrians = Antrian::where('desa_id', auth()->user()->desa_id)->where('tanggal_antri', now()->format('Y-m-d'))->get();
        }

        $loket = Loket::where('desa_id', auth()->user()->desa_id)->get();

        return view('desa.antrian.index', compact('antrians', 'loket'));
    }

    public function create()
    {
        $no_antri = Antrian::where('tanggal_antri', date('Y-m-d'))->where('status', 0)->count();
        $warga = Warga::where('desa_id', auth()->user()->desa_id)->get();
        $jenis = JenisSurat::get();
        $antrian = new Antrian();
        $loket = Loket::where('desa_id', auth()->user()->desa_id)->get();

        return view('desa.antrian.create', compact('warga', 'no_antri', 'jenis', 'antrian', 'loket'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_antrian' => 'required',
            'warga' => 'required',
            'jenis' => 'required',
            'loket' => 'required',
        ]);
        $attr = $request->except('jenis', 'warga', 'loket');

        $attr['warga_id'] = $request->warga;
        $attr['loket_id'] = $request->loket;
        $attr['jenis_surat_id'] = $request->jenis;
        $attr['desa_id'] = auth()->user()->desa_id;
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
        $warga = Warga::where('desa_id', auth()->user()->desa_id)->get();
        $jenis = JenisSurat::get();
        $loket = Loket::where('desa_id', auth()->user()->desa_id)->get();

        return view('desa.antrian.edit', compact('warga', 'jenis', 'antrian', 'loket'));
    }

    public function update(Request $request, Antrian $antrian)
    {
        $request->validate([
            'no_antrian' => 'required',
            'warga' => 'required',
            'jenis' => 'required',
            'loket' => 'required',
        ]);
        $attr = $request->except('jenis', 'warga', 'loket');

        $attr['warga_id'] = $request->warga;
        $attr['jenis_surat_id'] = $request->jenis;
        $attr['loket_id'] = $request->loket;
        $attr['desa_id'] = auth()->user()->desa_id;

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
        // return $antrian;
        if ($antrian->status == 0) {
            $antrian->update(['status' => 1]);
            $message = 'Nomor antrian telah dipanggil';

            return response()->json([
                'message' => $message
            ]);
        }
        if ($antrian->status == 1) {
            $antrian->update(['status' => 2]);
            PermohonanSurat::create([
                'jenis_surat_id' => $antrian->jenis_surat_id,
                'warga_id' => $antrian->warga_id,
                'desa_id' => $antrian->desa_id,
            ]);

            Alert::success('Selamat', 'Antrian berhasil dikonfirmasi');
            return back();
        }
    }
}
