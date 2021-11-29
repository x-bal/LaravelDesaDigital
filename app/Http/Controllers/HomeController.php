<?php

namespace App\Http\Controllers;

use App\Models\Aduan;
use App\Models\Antrian;
use App\Models\JenisSurat;
use App\Models\Loket;
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
        // $selesai = Antrian::with('loket')->where('tanggal_antri', now('Asia/Jakarta')->format('Y-m-d'))->where('status', 2)->get();
        $no_antri = Antrian::where('tanggal_antri', now('Asia/Jakarta')->format('Y-m-d'))->count();
        $sisaloket = Loket::where('kuota', '>', 0)->count();
        $loket = Loket::get();
        $jenis = JenisSurat::get();

        return view('landing.antrian', compact('no_antri', 'jenis',  'loket', 'sisaloket'));
    }

    public function storeAntrian(Request $request)
    {
        $request->validate([
            'nik' => 'required',
            'jenis' => 'required',
        ]);

        try {
            $warga = Warga::where('nik', $request->nik)->firstOrFail();

            $loket = Loket::where('desa_id', $warga->desa_id)->where('kuota', '>', 0)->first();
            if ($loket) {
                Antrian::create([
                    'warga_id' => $warga->id,
                    'jenis_surat_id' => $request->jenis,
                    'no_antrian' => $request->no_antri,
                    'desa_id' => $warga->desa_id,
                    'loket_id' => $loket->id
                ]);
                $sisa = $loket->kuota - 1;
                $loket->update(['kuota' => $sisa]);

                Alert::success('Selamat!', 'Pendaftaran antrian berhasil dilakukan');
                return back();
            } else {
                Alert::error('Error!', 'Mohon maaf nomor antrian telah habis');
                return back();
            }
        } catch (\Throwable $th) {
            Alert::error('Error!', $th->getMessage());
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
                'aduan' => request('aduan'),
            ]);

            Alert::success('Success!', 'Aduan berhasil dikirim');
            return back();
        } catch (\Throwable $th) {
            Alert::error('Error!', 'Mohon periksa kembali data yang anda masukkan');
            return back();
        }
    }
}
