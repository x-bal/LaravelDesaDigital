<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\Rate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;

class RateController extends Controller
{
    public function index()
    {
        $rates = Rate::where('desa_id', auth()->user()->desa->first()->id)->get();
        return view('desa.rates.index', compact('rates'));
    }

    public function create()
    {
        $rate = new Rate();
        return view('desa.rates.create', compact('rate'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'icon' => 'required',
        ]);

        try {
            $icon = $request->file('icon');
            $iconUrl = $icon->storeAs('desa/icon', Str::slug($request->name) . '.' . $icon->extension());

            Rate::create([
                'name' => $request->name,
                'desa_id' => auth()->user()->desa->first()->id,
                'icon' => $iconUrl
            ]);

            Alert::success('Selamat!', 'Data Rate berhasil ditambahkan');
            return redirect()->route('desa.rates.index');
        } catch (\Throwable $th) {
            Alert::error('Error!', $th->getMessage());
            return back();
        }
    }

    public function show($id)
    {
        //
    }

    public function edit(Rate $rate)
    {
        return view('desa.rates.edit', compact('rate'));
    }

    public function update(Request $request, Rate $rate)
    {
        $request->validate([
            'name' => 'required',
            'icon' => 'required',
        ]);

        try {
            if ($request->file('icon')) {
                Storage::delete($rate->icon);
                $icon = $request->file('icon');
                $iconUrl = $icon->storeAs('desa/icon', Str::slug($request->name) . '.' . $icon->extension());
            } else {
                $iconUrl = $rate->icon;
            }

            $rate->update([
                'name' => $request->name,
                'desa_id' => auth()->user()->desa->first()->id,
                'icon' => $iconUrl
            ]);

            Alert::success('Selamat!', 'Data Rate berhasil diupdate');
            return redirect()->route('desa.rates.index');
        } catch (\Throwable $th) {
            Alert::error('Error!', $th->getMessage());
            return back();
        }
    }

    public function destroy(Rate $rate)
    {
        try {
            Storage::delete($rate->icon);
            $rate->delete();

            Alert::success('Selamat!', 'Data Rate berhasil didelete');
            return redirect()->route('desa.rates.index');
        } catch (\Throwable $th) {
            Alert::error('Error!', $th->getMessage());
            return back();
        }
    }
}
