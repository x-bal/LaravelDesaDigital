<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Informasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InformasiController extends Controller
{
    public function getinformasi()
    {
        try {
            $informasi = Informasi::where('desa_id', Auth::user()->desa_id)->latest()->get();
            return response()->json($informasi);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }
}
