<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Warga;
use Illuminate\Http\Request;

class DevController extends Controller
{
    public function getcetaksurat(Request $request)
    {
        $data = [];
        $resources = Warga::where('nama_warga','like','%'.$request->q.'$')->orWhere('nik','like','%'.$request->q.'%')->get();
        foreach($resources as $resource){
            $data[] = ['id'=>$resource->id,'text'=>$resource->nama_warga.' - '.$resource->nik];
        }
        return response()->json($data);
    }
    public function showcetaksurat($id)
    {
        $resource = Warga::findOrFail($id);
        return response()->json($resource);
    }
}
