<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\Playlist;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class PlaylistController extends Controller
{
    public function index()
    {
        $playlists = Playlist::where('desa_id', auth()->user()->desa_id)->get();
        return view('desa.playlist.index', compact('playlists'));
    }

    public function create()
    {
        return view('desa.playlist.create');
    }

    public function store(Request $request)
    {
        $request->validate(['video' => 'required']);

        try {
            $video = $request->file('video');
            $videoUrl = $video->storeAs('desa/playlist', $video->getClientOriginalName());

            Playlist::create([
                'desa_id' => auth()->user()->desa_id,
                'video' => $videoUrl
            ]);

            Alert::success('Selamat', 'video berhasil diupload');
            return redirect()->route('desa.playlist.index');
        } catch (\Throwable $th) {
            Alert::error('Selamat', $th->getMessage());
            return back();
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function getVideo(Playlist $playlist)
    {
        $playlist->update([
            'status' => 1
        ]);

        $playlists = Playlist::where('id', '!=', $playlist->id)->get();
        foreach ($playlists as $play) {
            $play->update(['status' => 0]);
        }

        $total = Playlist::count();
        $bind = $playlist->id + 1;

        if ($bind <= $total) {
            $video = Playlist::where('id', $bind)->first();
        } else {
            $video = Playlist::where('status', 0)->first();
        }

        return $video;
    }
}
