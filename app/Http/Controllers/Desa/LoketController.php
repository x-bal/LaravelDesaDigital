<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\Loket;
use App\Models\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class LoketController extends Controller
{
    public function index()
    {
        $lokets = Loket::where('desa_id', auth()->user()->desa_id)->get();
        return view('desa.loket.index', compact('lokets'));
    }

    public function create()
    {
        $loket = new Loket();
        $admin = User::where('desa_id', auth()->user()->desa_id)->get();

        return view('desa.loket.create', compact('loket', 'admin'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'kuota' => 'required',
        ]);

        try {
            $loket = Loket::create([
                'nama' => $request->nama,
                'kuota' => $request->kuota,
                'desa_id' => auth()->user()->desa_id
            ]);

            $user = User::find($request->admin);
            $user->update(['loket_id' => $loket->id]);

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
        $admin = User::where('desa_id', auth()->user()->desa_id)->get();

        return view('desa.loket.edit', compact('loket', 'admin'));
    }

    public function update(Request $request, Loket $loket)
    {
        $request->validate([
            'nama' => 'required',
            'kuota' => 'required',
        ]);

        try {
            $loket->update([
                'nama' => $request->nama,
                'kuota' => $request->kuota,
            ]);

            $user = User::find($request->admin);
            $user->update([
                'loket_id' => $loket->id
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

    public function reset(Loket $loket)
    {
        $loket->update(['kuota' => 20]);

        Alert::success('Success!', 'Kuota loket berhasil direset');
        return redirect()->route('desa.loket.index');
    }
}
