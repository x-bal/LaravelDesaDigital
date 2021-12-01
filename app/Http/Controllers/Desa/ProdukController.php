<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\Desa;
use App\Models\Photo;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('desa.produk.index', [
            'produks' => Produk::get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('desa.produk.create', [
            'produk' => new Produk(),
            'desas' => Desa::get()
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
        $attr = $this->validate($request, [
            'nama_produk' => 'required',
            'harga' => 'required',
            'deskripsi' => 'required',
            'desa_id' => 'required'
        ]);
        unset($attr['photo']);
        $attr['user_id'] = auth()->user()->id;
        $produk = Produk::create($attr);
        foreach ($request->photo as $img) {
            $name = $img->getClientOriginalName();
            $path = $img->storeAs('produk/', $name);
            Photo::create([
                'produk_id' => $produk->id,
                'photo' => $path
            ]);
        }
        Alert::success('Success');
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
        return view('desa.produk.edit', [
            'produk' => Produk::findOrFail($id),
            'desas' => Desa::get()
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
        $attr = $this->validate($request, [
            'nama_produk' => 'required',
            'harga' => 'required',
            'deskripsi' => 'required',
            'desa_id' => 'required'
        ]);
        $attr['user_id'] = auth()->user()->id;
        $produk = Produk::findOrFail($id)->update($attr);
        if ($request->photo) {

            foreach(Produk::findOrFail($id)->photo as $img){

                Storage::delete($img->photo);
            }
            Photo::where('produk_id',$id)->delete();
            foreach ($request->photo as $img) {
                $name = $img->getClientOriginalName();
                $path = $img->storeAs('produk/', $name);
                Photo::create([
                    'produk_id' => $id,
                    'photo' => $path
                ]);
            }

        }
        Alert::success('Success');
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
            $produk = Produk::findOrFail($id);
            foreach ($produk->photo as $photo) {
                Storage::delete($photo->photo);
            }
            Photo::where('produk_id', $produk->id)->delete();
            $produk->delete();
            Alert::success('Success', 'Success Delete Produk');
            return back();
        } catch (\Throwable $th) {
            Alert::error('Error', $th->getMessage());
            return back();
        }
    }
}
