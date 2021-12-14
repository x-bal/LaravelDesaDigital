<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\Marque;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class MarqueController extends Controller
{
    public function index()
    {
        $marques = Marque::where('desa_id', auth()->user()->desa->first()->id)->get();
        return view('desa.marque.index', compact('marques'));
    }

    public function create()
    {
        $marque = new Marque();
        return view('desa.marque.create', compact('marque'));
    }

    public function store(Request $request)
    {
        $request->validate(['text' => 'required']);

        try {
            Marque::create([
                'desa_id' => auth()->user()->desa->first()->id,
                'marque' => $request->text
            ]);

            Alert::success('Selamat', 'Marque berhasil ditambahkan');
            return redirect()->route('desa.marque.index');
        } catch (\Throwable $th) {
            Alert::error('Error', $th->getMessage());
            return back();
        }
    }

    public function show(Marque $marque)
    {
        //
    }

    public function edit(Marque $marque)
    {
        //
    }

    public function update(Request $request, Marque $marque)
    {
        //
    }

    public function destroy(Marque $marque)
    {
        //
    }
}
