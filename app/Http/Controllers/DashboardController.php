<?php

namespace App\Http\Controllers;

use App\Models\Aduan;
use App\Models\Produk;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $warga = Warga::get()->count();
        $aduan = Aduan::get()->count();
        $pengguna = User::whereHas('roles', function($qr) { return $qr->where('name','Warga');  })->get()->count();
        $produk = Produk::get()->count();
        return view('dashboard.index',[
            'warga' => $warga,
            'aduan' => $aduan,
            'pengguna' => $pengguna,
            'produk' => $produk
        ]);
    }
}
