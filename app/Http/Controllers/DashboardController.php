<?php

namespace App\Http\Controllers;

use App\Models\Aduan;
use App\Models\Desa;
use App\Models\Produk;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;

class DashboardController extends Controller
{
    public function index()
    {
        $warga = Warga::get()->count();
        $aduan = Aduan::get()->count();
        $pengguna = User::whereHas('roles', function ($qr) {
            return $qr->where('name', 'Warga');
        })->get()->count();
        $produk = Produk::get()->count();
        return view('dashboard.index', [
            'warga' => $warga,
            'aduan' => $aduan,
            'pengguna' => $pengguna,
            'produk' => $produk
        ]);
    }

    public function setting()
    {
        $desa = Desa::find(auth()->user()->desa[0]->id);
        return view('dashboard.setting', compact('desa'));
    }

    public function updateSetting(Desa $desa)
    {
        request()->validate([
            'background' => 'required'
        ]);

        Storage::delete($desa->background);
        $background = request()->file('background');
        $backgroundUrl = $background->storeAs('desa/background/', Str::slug($desa->nama_desa) . '.' . $background->extension());

        $desa->update(['background' => $backgroundUrl]);

        Alert::success('Selamat', 'Background berhasil diubah');
        return back();
    }
}
