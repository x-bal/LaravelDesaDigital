<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Models\Desa;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Warga;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class MasyarakatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $wargas = Warga::where('is_nik',auth()->user()->warga->nik)->orderBy('created_at', 'desc')->get();
        return view('warga.masyarakat.index', [
            'wargas' => $wargas
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('warga.masyarakat.create', [
            'warga' => new Warga(),
            'desas' => Desa::get(),
            'kecamatans' => Kecamatan::get(),
            'kabupatens' => Kabupaten::get()
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
            'nik' => 'required|unique:wargas',
            'nama_warga' => 'required',
            'jenis_kelamin' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'desa_id' => 'required',
            'kecamatan_id' => 'required',
            'kabupaten_id' => 'required',
        ]);
        $attr['is_nik'] = auth()->user()->warga->nik;
        Warga::create($attr);
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
        return view('warga.masyarakat.edit', [
            'warga' => Warga::findOrFail($id),
            'desas' => Desa::get(),
            'kecamatans' => Kecamatan::get(),
            'kabupatens' => Kabupaten::get()
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
            'nik' => 'required',
            'nama_warga' => 'required',
            'jenis_kelamin' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'desa_id' => 'required',
            'kecamatan_id' => 'required',
            'kabupaten_id' => 'required',
        ]);
        $attr['is_nik'] = auth()->user()->warga->nik;
        Warga::findOrFail($id)->update($attr);
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
            Warga::findOrFail($id)->delete();
            Alert::success('success');
            return back();
        } catch (\Throwable $th) {
            Alert::error($th->getMessage());
            return back();
        }
    }
}
