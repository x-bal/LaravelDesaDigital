<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Aduan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AduanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $aduan = Aduan::where('warga_id', Auth::user()->warga->first()->id)->latest()->get();
            return response()->json($aduan);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'aduan' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->getMessageBag());
        }
        $attr = $request->all();
        try {
            $attr['desa_id'] = Auth::user()->warga->first()->desa_id;
            $attr['warga_id'] = Auth::user()->warga->first()->id;

            $response = Aduan::create($attr);
            return response()->json($response);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $response = Aduan::findOrFail($id);
        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'aduan' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->getMessageBag());
        }
        $attr = $request->all();
        try {
            $response = Aduan::findOrFail($id)->update($attr);
            return response()->json($response);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $response = Aduan::findOrFail($id)->delete();
            return response()->json($response);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }
}
