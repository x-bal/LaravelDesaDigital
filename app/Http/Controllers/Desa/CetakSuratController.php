<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\JenisSurat;
use App\Models\PengajuanWarga;
use App\Models\PermohonanSurat;
use App\Models\PermohonanSuratSkck;
use App\Models\Warga;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $warga = Warga::findOrFail($request->warga_id);
        switch ($request->permohonan_surat_id) {
            case 1:
                $doc = '1_surat_ket_catatan_kriminal';
                $array = array(
                    '[nama_kab]' => $warga->desa->kecamatan->kabupaten->nama_kabupaten,
                    '[nama_kec]' => $warga->desa->kecamatan->nama_kecamatan,
                    '[nama_des]' => $warga->desa->nama_desa,
                    '[nama_des_text]' => $warga->desa->nama_desa,
                    '[alamat_des]' => $warga->desa->alamat,
                    '[judul_surat]' => 'Surat Keterangan Catatan Kriminal',
                    '[format_nomor_surat]' => '69965590',
                    '[jabatan]' => 'Admin',
                    '[nama_provinsi]' => $warga->desa->kecamatan->kabupaten->provinsi->nama_provinsi,
                    '[nama]' => $warga->nama_warga,
                    '[no_ktp]' => $warga->nik,
                    '[no_kk]' => '118066346634',
                    '[kepala_kk]' => '663411806634',
                    '[ttl]' => $warga->tempat_lahir . ', ' . $warga->tanggal_lahir,
                    '[agama]' => 'Islam',
                    '[sex]' => $warga->jenis_kelamin,
                    '[alamat]' => 'Jalan Lembak Desa Kemang',
                    '[status]' => 'Pelajar',
                    '[pendidikan]' => $request->pendidikan,
                    '[pekerjaan]' => $request->pekerjaan,
                    '[warga_negara]' => 'Indonesia',
                    '[form_keterangan]' => 'NJir bang',
                    '[tgl_surat]' => Carbon::now()->format('Y F d'),
                    '[penandatangan]' => $warga->nama_warga,
                    '[nama_pamong]' => auth()->user()->name,
                    '[nip_pamong]' => '123123',
                    '[kode_desa]' => '1029380912',
                    '[kode_surat]' => '123890182'
                );
                break;
            case 2:
                $doc = '2_surat_ket_kehilangan';
                break;
            case 3:
                $doc = '3_surat_izin_keramaian';
                break;
            case 4:
                $doc = '4_surat_ket_pengantar';
                break;
            case 5:
                $doc = '5_surat_kuasa';
                break;
            case 6:
                $doc = '6_surat_ket_usaha';
                break;
            case 7:
                $doc = '7_surat_ket_domisili_usaha';
                break;
            case 8:
                $doc = '8_surat_ket_pergi_kawin';
                break;
            case 9:
                $doc = '9_surat_ket_penghasilan_orangtua';
                break;
            case 10:
                $doc = 'permohonan_surat_jaminan_kesehatans';
                break;
            case 11:
                $doc = 'permohonan_surat_kurang_mampus';
                break;
            default:
                $doc = abort(404);
                break;
        }
        $file = public_path('template/' . $doc . '.rtf');

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
        if (strpos(url()->previous(), 'cetak_surat')) {
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
        if (strpos(url()->previous(), 'permohonan')) {
            $surat = PermohonanSurat::findOrFail($id);
            $jenis_surat = JenisSurat::findOrFail($surat->jenis_surat_id);
            $warga = Warga::findOrFail($surat->warga_id);
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
            return view('desa.cetak_surat.edit', [
                'jenis_surat' => $jenis_surat,
                'table' => $table,
                'warga' => $warga,
                'surat' => $surat
            ]);
        }
        abort(404);
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
        $warga = Warga::findOrFail($request->warga_id);
        DB::beginTransaction();
        try {
            switch ($request->permohonan_surat_id) {
                case 1:
                    PengajuanWarga::create([
                        'permohonan_surat_id' => $id,
                        'nama' => $warga->nama_warga,
                        'nik' => $warga->nik,
                        'tempat_lahir' => $warga->tempat_lahir,
                        'jenis_kelamin' => $warga->jenis_kelamin,
                        'alamat' => $warga->desa->nama_desa . ' ,' . $warga->desa->alamat,
                        'agama' => 'islam',
                        'status_perkawinan' => 'belum menikah',
                        'pekerjaan' => 'dpr',
                        'kewarganegaraan' => 'swedia',
                        'golongan_darah' => '0'
                    ]);
                    PermohonanSuratSkck::create([
                        'permohonan_surat_id' => $id,
                        'keperluan' => $request->keperluan,
                        'pendidikan' => $request->pendidikan,
                        'pekerjaan' => $request->pekerjaan,
                    ]);

                    $doc = '1_surat_ket_catatan_kriminal';
                    $array = array(
                        '[nama_kab]' => $warga->desa->kecamatan->kabupaten->nama_kabupaten,
                        '[nama_kec]' => $warga->desa->kecamatan->nama_kecamatan,
                        '[nama_des]' => $warga->desa->nama_desa,
                        '[nama_des_text]' => $warga->desa->nama_desa,
                        '[alamat_des]' => $warga->desa->alamat,
                        '[judul_surat]' => 'Surat Keterangan Catatan Kriminal',
                        '[format_nomor_surat]' => '69965590',
                        '[jabatan]' => 'Admin',
                        '[nama_provinsi]' => $warga->desa->kecamatan->kabupaten->provinsi->nama_provinsi,
                        '[nama]' => $warga->nama_warga,
                        '[no_ktp]' => $warga->nik,
                        '[no_kk]' => '118066346634',
                        '[kepala_kk]' => '663411806634',
                        '[ttl]' => $warga->tempat_lahir . ', ' . $warga->tanggal_lahir,
                        '[agama]' => 'Islam',
                        '[sex]' => $warga->jenis_kelamin,
                        '[alamat]' => 'Jalan Lembak Desa Kemang',
                        '[status]' => 'Pelajar',
                        '[pendidikan]' => $request->pendidikan,
                        '[pekerjaan]' => $request->pekerjaan,
                        '[warga_negara]' => 'Indonesia',
                        '[form_keterangan]' => 'NJir bang',
                        '[tgl_surat]' => Carbon::now()->format('Y F d'),
                        '[penandatangan]' => $warga->nama_warga,
                        '[nama_pamong]' => auth()->user()->name,
                        '[nip_pamong]' => '123123',
                        '[kode_desa]' => '1029380912',
                        '[kode_surat]' => '123890182'
                    );
                    DB::commit();
                    break;
                case 2:
                    $doc = '2_surat_ket_kehilangan';
                    break;
                case 3:
                    $doc = '3_surat_izin_keramaian';
                    break;
                case 4:
                    $doc = '4_surat_ket_pengantar';
                    break;
                case 5:
                    $doc = '5_surat_kuasa';
                    break;
                case 6:
                    $doc = '6_surat_ket_usaha';
                    break;
                case 7:
                    $doc = '7_surat_ket_domisili_usaha';
                    break;
                case 8:
                    $doc = '8_surat_ket_pergi_kawin';
                    break;
                case 9:
                    $doc = '9_surat_ket_penghasilan_orangtua';
                    break;
                case 10:
                    $doc = 'permohonan_surat_jaminan_kesehatans';
                    break;
                case 11:
                    $doc = 'permohonan_surat_kurang_mampus';
                    break;
                default:
                    $doc = abort(404);
                    break;
            }
            $file = public_path('template/' . $doc . '.rtf');

            $nama_file = 'surat-keterangan-kerja.doc';

            return \WordTemplate::export($file, $array, $nama_file);
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th->getMessage());
        }
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
