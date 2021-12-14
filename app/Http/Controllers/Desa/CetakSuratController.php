<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\JenisSurat;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CetakSuratController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jenis_surat = JenisSurat::get();
        return view('desa.cetak_surat.index', [
            'jenis_surat' => $jenis_surat
        ]);
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
        // dd($request->all());
        $file = public_path('template/surat_hilang.rtf');

        $array = array(
            '[nama_kab]' => 'BOGOR',
            '[nama_kec]' => 'Bojonggede',
            '[nama_des]' => 'Jl. Manunggal Gg. 8 Loa Bakung, Samarinda',
            '[alamat_des]' => 'Jl. Manunggal Gg. 8 Loa Bakung, Samarinda',
            '[judul_surat]' => 'Samarinda',
            '[format_nomor_surat]' => 'Noviyanto Rahmadi',
            '[jabatan]' => date('d F Y'),
        );

        $nama_file = 'surat-keterangan-kerja.doc';

        return \WordTemplate::export($file, $array, $nama_file);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $jenis_surat = JenisSurat::findOrFail($id);
        switch ($jenis_surat->id) {
            case 1:
                $table = 'permohonan_surat_skcks';
                break;
            case 2:
                $table = 'permohonan_surat_kehilangans';
                break;
            case 3:
                $table = 'permohonan_surat_izin_keramaians';
                break;
            case 4:
                $table = 'permohonan_surat_pengantars';
                break;
            case 5:
                $table = 'permohonan_surat_kuasas';
                break;
            case 6:
                $table = 'permohonan_surat_usahas';
                break;
            case 7:
                $table = 'permohonan_surat_domisili_usahas';
                break;
            case 8:
                $table = 'permohonan_surat_pergi_kawins';
                break;
            case 9:
                $table = '';
                break;
            case 10:
                $table = 'permohonan_surat_jaminan_kesehatans';
                break;
            case 11:

                $table = 'permohonan_surat_kurang_mampus';
                break;
            default:
                $table = '';
                break;
        }
        return view('desa.cetak_surat.show', [
            'jenis_surat' => $jenis_surat,
            'table' => $table
        ]);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
