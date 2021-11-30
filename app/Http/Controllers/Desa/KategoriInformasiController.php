<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\KategoriInformasi;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class KategoriInformasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('desa.kategori_informasi.index',[
            'kategori_informasis' => KategoriInformasi::get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('desa.kategori_informasi.create',[
            'kategori_informasi' => new KategoriInformasi()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attr = $this->validate($request,[
            'nama' => 'required'
        ]);
        KategoriInformasi::create($attr);
        Alert::success('success');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('desa.kategori_informasi.edit',[
            'kategori_informasi' => KategoriInformasi::findOrFail($id)
        ]);
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
        $attr = $this->validate($request,[
            'nama' => 'required'
        ]);
        KategoriInformasi::findOrFail($id)->update($attr);
        Alert::success('success');
        return back();
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
            //code...
            KategoriInformasi::findOrFail($id)->delete();
            Alert::success('success');
        } catch (\Throwable $th) {
            //throw $th;
            Alert::error($th->getMessage());
            return back();
        }
    }
}
