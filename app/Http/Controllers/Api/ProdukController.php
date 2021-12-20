<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProdukResource;
use App\Models\Photo;
use App\Models\Produk;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $produk = Produk::where('desa_id', $request->user()->desa_id)->with('photo')->latest()->get();
        $response = ProdukResource::collection($produk);
        return response()->json($response);
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
            'nama_produk' => 'required',
            'harga' => 'required',
            'deskripsi' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->getMessageBag());
        }
        $attr = $request->all();
        $attr['desa_id'] = Auth::user()->warga->desa_id;
        $attr['user_id'] = Auth::user()->id;
        try {
            $produk = Produk::create($attr);
            foreach ($request->photo as $img) {
                $name = $img->getClientOriginalName();
                $path = $img->storeAs('produk/', $name);
                Photo::create([
                    'produk_id' => $produk->id,
                    'photo' => $path
                ]);
            }
            return response()->json($produk);
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
        $produk = Produk::with('photo')->findOrFail($id);
        $response = new ProdukResource($produk);
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
        $attr = $this->validate($request, [
            'nama_produk' => 'required',
            'harga' => 'required',
            'deskripsi' => 'required'
        ]);
        $attr['desa_id'] = Auth::user()->warga->desa_id;
        $attr['user_id'] = Auth::user()->id;
        $produk = Produk::findOrFail($id)->update($attr);
        if ($request->photo) {

            foreach (Produk::findOrFail($id)->photo as $img) {

                Storage::delete($img->photo);
            }
            Photo::where('produk_id', $id)->delete();
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
            return response()->json('success deleted produk');
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }
}
