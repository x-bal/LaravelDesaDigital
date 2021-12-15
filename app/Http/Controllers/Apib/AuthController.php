<?php

namespace App\Http\Controllers\Apib;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    

    public function login(Request $request)
    {
        // if (!Auth::attempt($request->only('username', 'password'))) {
        //     return response()
        //         ->json(['message' => 'Unauthorized'], 401);
        // }
        
        $user = DB::select("
        select * from users where email = 'test@g.com' 
        ");
        $response['error'] = false;
		$response['id'] = $user[0]->id;
		$response['email'] = $user[0]->email;
		$response['username'] = $user[0]->username;

        return response()->json($response);  
    }

    // public function login(Request $request)
    // {
    //     $user = DB::select("
    //     select * from users where email = 'test@g.com' 
    //     ");
    //     //$user = User::where('email', 'desa@gmail.com')->firstOrFail();
    //     //$user = User::where('email', $request['email'])->firstOrFail();
    //     //dd($user[0]->id);
    //     $response['error'] = false;
	// 	$response['id'] = $user[0]->id;
	// 	$response['email'] = $user[0]->email;
	// 	$response['username'] = $user[0]->username;

    //     return response()->json($response);        
    // }

    public function aduan(Request $request)
    {
        $v_aduan = DB::select("
            select
            aduans.*,
            wargas.id as wargas_id,
            wargas.desa_id,
            wargas.kecamatan_id,
            wargas.kabupaten_id,
            wargas.user_id,
            wargas.nik,
            wargas.nama_warga,
            wargas.jenis_kelamin,
            wargas.tempat_lahir,
            wargas.tanggal_lahir,
            wargas.is_nik,
            desas.id as desas_id ,desas.kecamatan_id,desas.nama_desa,desas.background,
            kecamatans.id as kecamatans_id,kecamatans.kabupaten_id,kecamatans.nama_kecamatan,
            kabupatens.id as kabupatens_id,kabupatens.provinsi_id,kabupatens.nama_kabupaten
            from
            aduans
            inner join wargas
            on
            aduans.warga_id = wargas.id
            left join desas 
            on
            wargas.desa_id = desas.id
            left join kecamatans
            on
            wargas.kecamatan_id = kecamatans.id		
            left join kabupatens
            on
            wargas.kabupaten_id = kabupatens.id
        ");
        $data = array();
        $qry_array = array();
        $i = 0;
        $total = 0;
        foreach ($v_aduan as $v_v_aduan) {
            $total = $total++;
            $data['id'] =  $v_v_aduan->id;
            $data['aduan'] = $v_v_aduan->aduan;
            $data['respon'] = $v_v_aduan->respon;
            $data['nama_warga'] = $v_v_aduan->nama_warga;
            $data['created_at'] = $v_v_aduan->created_at;
            $qry_array[$i] = $data;
            $i++;
        }

        if($v_aduan) {
            $response['success'] = 'true';
            $response['message'] = 'Data Loaded Successfully';
            $response['total'] = $total;
            $response['data'] = $qry_array;
        } else {
            $response['success'] = 'false';
            $response['message'] = 'Data Loading Failed';
          }

        return response()->json($response);      
        //echo json_encode($response);  
    }


    public function datas(Request $request)
    {
        $v_aduan = DB::select("
        SELECT * FROM data
        ");
        $data = array();
        $qry_array = array();
        $i = 0;
        $total = 0;
        foreach ($v_aduan as $v_v_aduan) {
            $total = $total++;
            $data['id'] =  $v_v_aduan->id;
            $data['uid'] = $v_v_aduan->uid;
            $data['name'] = $v_v_aduan->name;
            $data['phone'] = $v_v_aduan->phone;
            $data['address'] = $v_v_aduan->address;
            $qry_array[$i] = $data;
            $i++;
        }

        if($v_aduan) {
            $response['success'] = 'true';
            $response['message'] = 'Data Loaded Successfully';
            $response['total'] = $total;
            $response['data'] = $qry_array;
        } else {
            $response['success'] = 'false';
            $response['message'] = 'Data Loading Failed';
          }

        //return response()->json($response);      
        echo json_encode($response);  
    }
    
    
}
