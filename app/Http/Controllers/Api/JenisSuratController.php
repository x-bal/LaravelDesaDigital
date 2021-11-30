<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JenisSurat;
use Illuminate\Http\Request;

class JenisSuratController extends Controller
{
    public function getjenissurat()
    {
        $response = JenisSurat::get();
        return response()->json($response);
    }
}
