<?php

namespace App\Http\Controllers;

use App\Models\Aduan;
use App\Models\Antrian;
use App\Models\JenisSurat;
use App\Models\Warga;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class HomeController extends Controller
{
    public function index()
    {
        return view('landing.home');
    }

    public function antrian()
    {
        $no_antri = Antrian::where('tanggal_antri', now('Asia/Jakarta')->format('Y-m-d'))->count();
        $selesai = Antrian::where('tanggal_antri', now('Asia/Jakarta')->format('Y-m-d'))->where('status', 2)->count();
        $jenis = JenisSurat::get();

        return view('landing.antrian', compact('no_antri', 'jenis', 'selesai'));
    }

    public function storeAntrian(Request $request)
    {
        $request->validate([
            'nik' => 'required',
            'jenis' => 'required',
        ]);

        try {
            $warga = Warga::where('nik', $request->nik)->firstOrFail();

            Antrian::create([
                'warga_id' => $warga->id,
                'jenis_surat_id' => $request->jenis,
                'no_antrian' => $request->no_antri,
                'desa_id' => $warga->desa_id
            ]);

            Alert::success('Selamat!', 'Pendaftaran antrian berhasil dilakukan');
            return back();
        } catch (\Throwable $th) {
            Alert::error('Error!', 'Mohon periksa kembali data yang anda masukkan');
            return back();
        }
    }

    public function aduan()
    {
        return view('landing.aduan');
    }

    public function storeAduan()
    {
        request()->validate([
            'nik' => 'required',
            'aduan' => 'required'
        ]);

        try {
            $warga = Warga::where('nik', request('nik'))->firstOrFail();

            Aduan::create([
                'warga_id' => $warga->id,
                'desa_id' => $warga->desa_id,
                'aduan' => request('aduan')
            ]);

            Alert::success('Success!', 'Aduan berhasil dikirim');
            return back();
        } catch (\Throwable $th) {
            Alert::error('Error!', 'Mohon periksa kembali data yang anda masukkan');
            return back();
        }
    }
}
