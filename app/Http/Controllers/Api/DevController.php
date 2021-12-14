<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Warga;
use Carbon\Carbon;
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
        $response = [
            'resource' => $resource,
            'umur' =>  Carbon::now()->format('Y') - Carbon::parse($resource->tanggal_lahir)->format('Y')
        ];
        return response()->json($response);
    }
}
