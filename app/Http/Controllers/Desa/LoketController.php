<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\Loket;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class LoketController extends Controller
{
    public function index()
    {
        $lokets = Loket::where('desa_id', auth()->user()->desa[0]->id)->get();

        return view('desa.loket.index', compact('lokets'));
    }

    public function create()
    {
        $loket = new Loket();

        return view('desa.loket.create', compact('loket'));
    }

    public function store(Request $request)
    {
        $request->validate(['nama' => 'required']);

        try {
            Loket::create([
                'nama' => $request->nama,
                'desa_id' => auth()->user()->desa[0]->id
            ]);

            Alert::success('Success!', 'Loket berhasil dibuat');
            return redirect()->route('desa.loket.index');
        } catch (\Throwable $th) {
            Alert::error('Error!', 'Something when wrong!');
            return back();
        }
    }

    public function show(Loket $loket)
    {
        //
    }

    public function edit(Loket $loket)
    {
        return view('desa.loket.edit', compact('loket'));
    }

    public function update(Request $request, Loket $loket)
    {
        $request->validate(['nama' => 'required']);

        try {
            $loket->update([
                'nama' => $request->nama,
            ]);

            Alert::success('Success!', 'Loket berhasil diupdate');
            return redirect()->route('desa.loket.index');
        } catch (\Throwable $th) {
            Alert::error('Error!', 'Something when wrong!');
            return back();
        }
    }

    public function destroy(Loket $loket)
    {
        $loket->delete();

        Alert::success('Success!', 'Loket berhasil didelete');
        return redirect()->route('desa.loket.index');
    }
}
