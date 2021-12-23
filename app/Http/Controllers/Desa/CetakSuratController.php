<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\DaftarKurangMampu;
use App\Models\Desa;
use App\Models\JenisSurat;
use App\Models\PengajuanWarga;
use App\Models\PermohonanSurat;
use App\Models\PermohonanSuratDomisiliUsaha;
use App\Models\PermohonanSuratIzinKeramaian;
use App\Models\PermohonanSuratJaminanKesehatan;
use App\Models\PermohonanSuratKehilangan;
use App\Models\PermohonanSuratKuasa;
use App\Models\PermohonanSuratKurangMampu;
use App\Models\PermohonanSuratPengantar;
use App\Models\PermohonanSuratPenghasilan;
use App\Models\PermohonanSuratPergiKawin;
use App\Models\PermohonanSuratSkck;
use App\Models\PermohonanSuratUsaha;
use App\Models\Warga;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

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

        $this->validate($request, [
            'warga_id' => 'required',
            'permohonan_surat_id' => 'required'
        ]);
        $warga = Warga::findOrFail($request->warga_id);
        DB::beginTransaction();
        $permohonan_surat_id = PermohonanSurat::create([
            'jenis_surat_id' => $request->permohonan_surat_id,
            'desa_id' => $warga->desa_id,
            'warga_id' => $warga->id
        ]);
        PengajuanWarga::create([
            'permohonan_surat_id' => $permohonan_surat_id->id,
            'nama' => $warga->nama_warga,
            'nik' => $warga->nik,
            'tempat_lahir' => $warga->tempat_lahir,
            'jenis_kelamin' => $warga->jenis_kelamin,
            'alamat' => $warga->desa->alamat,
            'agama' => $warga->agama,
            'status_perkawinan' => $warga->status_pernikahan,
            'pekerjaan' => $warga->pekerjaan,
            'kewarganegaraan' => $warga->warga_negara,
            'golongan_darah' => $warga->golongan_darah
        ]);
        switch ($request->permohonan_surat_id) {
            case 1:
                $doc = '1_surat_ket_catatan_kriminal';
                $this->validate($request, [
                    'permohonan_surat_id' => 'required',
                    'keperluan' => 'required',
                    'pendidikan' => 'required',
                    'pekerjaan' => 'required',
                ]);
                try {
                    PermohonanSuratSkck::create([
                        'permohonan_surat_id' => $permohonan_surat_id->id,
                        'keperluan' => $request->keperluan,
                        'pendidikan' => $request->pendidikan,
                        'pekerjaan' => $request->pekerjaan,
                    ]);
                    $body = array(
                        'nama' => $warga->nama_warga,
                        'no_ktp' => $warga->nik,
                        'no_kk' => $warga->kk,
                        'kepala_kk' => $warga->nama_warga,
                        'ttl' => $warga->tempat_lahir . '/' . Carbon::parse($warga->tanggal_lahir)->format('d F Y'),
                        'agama' => $warga->agama,
                        'sex' => $warga->jenis_kelamin,
                        'alamat' => $warga->desa->alamat,
                        'status' => $warga->status,
                        'pendidikan' => $request->pendidikan,
                        'pekerjaan' => $request->pekerjaan,
                        'warga_negara' => $warga->warga_negara,
                        'form_keterangan' => $request->keperluan,
                        'penandatangan' => $warga->nama_warga,
                    );
                    $array = array_merge(
                        $this->header($warga, [
                            'judul_surat' => 'Surat Keterangan Catatan Kriminal',
                            'nomor_surat' => '1010110'
                        ]),
                        $body,
                        $this->footer([
                            'kode_desa' => $warga->desa->id,
                            'kode_surat' => '1010110'
                        ])
                    );
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollBack();
                    Alert::error($th->getMessage());
                    return back();
                }
                break;
            case 2:
                $doc = '2_surat_ket_kehilangan';

                $this->validate($request, [
                    'permohonan_surat_id' => 'required',
                    'rincian_barang' => 'required',
                    'keterangan_hilang' => 'required',
                ]);
                try {
                    PermohonanSuratKehilangan::create([
                        'permohonan_surat_id' => $permohonan_surat_id->id,
                        'rincian_barang' => $request->rincian_barang,
                        'keterangan_hilang' => $request->keterangan_hilang
                    ]);
                    $body = array(
                        'nama' => $warga->nama_warga,
                        'no_ktp' => $warga->nik,
                        'no_kk' => $warga->kk,
                        'kepala_kk' => $warga->nama_warga,
                        'ttl' => $warga->tempat_lahir . '/' . Carbon::parse($warga->tanggal_lahir)->format('d F Y'),
                        'agama' => $warga->agama,
                        'sex' => $warga->jenis_kelamin,
                        'alamat' => $warga->desa->alamat,
                        'status' => $warga->status,
                        'pendidikan' => $warga->pendidikan,
                        'pekerjaan' => $warga->pekerjaan,
                        'warga_negara' => $warga->warga_negara,
                        'form_keterangan' => $request->keterangan_hilang,
                        'form_rincian' => $request->rincian_barang,
                        'form_barang' => $request->rincian_barang,
                        'penandatangan' => $warga->nama_warga,
                    );
                    $array = array_merge(
                        $this->header($warga, [
                            'judul_surat' => 'Permohonan Surat Izin Keramaian',
                            'nomor_surat' => '12312321'
                        ]),
                        $body,
                        $this->footer([
                            'kode_desa' => $warga->desa->id,
                            'kode_surat' => '1010110'
                        ])
                    );
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollBack();
                    Alert::error($th->getMessage());
                    return back();
                }

                break;
            case 3:
                $doc = '3_surat_izin_keramaian';
                $this->validate($request, [
                    'permohonan_surat_id' => 'required',
                    'berlaku_mulai' => 'required',
                    'berlaku_sampai' => 'required',
                    'jenis_keramaian' => 'required',
                    'keperluan' => 'required',
                ]);
                try {

                    PermohonanSuratIzinKeramaian::create([
                        'permohonan_surat_id' => $permohonan_surat_id->id,
                        'berlaku_mulai' => $request->berlaku_mulai,
                        'berlaku_sampai' => $request->berlaku_sampai,
                        'jenis_keramaian' => $request->jenis_keramaian,
                        'keperluan' => $request->keperluan
                    ]);
                    $body = array(
                        'nama' => $warga->nama_warga,
                        'no_ktp' => $warga->nik,
                        'no_kk' => $warga->kk,
                        'kepala_kk' => $warga->nama_warga,
                        'ttl' => $warga->tempat_lahir . '/' . Carbon::parse($warga->tanggal_lahir)->format('d F Y'),
                        'agama' => $warga->agama,
                        'sex' => $warga->jenis_kelamin,
                        'alamat' => $warga->desa->alamat,
                        'status' => $warga->status,
                        'pendidikan' => $warga->pendidikan,
                        'pekerjaan' => $warga->pekerjaan,
                        'warga_negara' => $warga->warga_negara,
                        'form_berlaku_dari' => $request->berlaku_mulai,
                        'form_berlaku_sampai' => $request->berlaku_sampai,
                        'form_jenis_keramaian' => $request->jenis_keramaian,
                        'form_keperluan' => $request->keperluan,
                        'penandatangan' => $warga->nama_warga,
                    );
                    $array = array_merge(
                        $this->header($warga, [
                            'judul_surat' => 'Permohonan Surat Izin Keramaian',
                            'nomor_surat' => '12312321'
                        ]),
                        $body,
                        $this->footer([
                            'kode_desa' => $warga->desa->id,
                            'kode_surat' => '1010110'
                        ])
                    );
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollBack();
                    Alert::error($th->getMessage());
                    return back();
                }
                break;
            case 4:
                $doc = '4_surat_ket_pengantar';
                $this->validate($request, [
                    'permohonan_surat_id' => 'required',
                    'berlaku_mulai' => 'required',
                    'berlaku_sampai' => 'required',
                    'keperluan' => 'required',
                ]);
                try {
                    PermohonanSuratPengantar::create([
                        'permohonan_surat_id' => $permohonan_surat_id->id,
                        'berlaku_mulai' => $request->berlaku_mulai,
                        'berlaku_sampai' => $request->berlaku_sampai,
                        'keperluan' => $request->keperluan
                    ]);
                    $body = array(
                        'nama' => $warga->nama_warga,
                        'no_ktp' => $warga->nik,
                        'no_kk' => $warga->kk,
                        'kepala_kk' => $warga->nama_warga,
                        'ttl' => $warga->tempat_lahir . '/' . Carbon::parse($warga->tanggal_lahir)->format('d F Y'),
                        'usia' => Carbon::now()->format('Y') - Carbon::parse($warga->tanggal_lahir)->format('Y'),
                        'agama' => $warga->agama,
                        'sex' => $warga->jenis_kelamin,
                        'alamat' => $warga->desa->alamat,
                        'status' => $warga->status,
                        'pendidikan' => $warga->pendidikan,
                        'pekerjaan' => $warga->pekerjaan,
                        'warga_negara' => $warga->warga_negara,
                        'gol_darah' => $warga->golongan_darah,
                        'mulai_berlaku' => $request->berlaku_mulai,
                        'tgl_akhir' => $request->berlaku_sampai,
                        'keperluan' => $request->keperluan,
                        'penandatangan' => $warga->nama_warga,
                    );
                    $array = array_merge(
                        $this->header($warga, [
                            'judul_surat' => 'Permohonan Surat Izin Keramaian',
                            'nomor_surat' => '12312321'
                        ]),
                        $body,
                        $this->footer([
                            'kode_desa' => $warga->desa->id,
                            'kode_surat' => '1010110'
                        ])
                    );
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollBack();
                    Alert::error($th->getMessage());
                    return back();
                }

                break;
            case 5:
                $doc = '5_surat_kuasa';
                $this->validate($request, [
                    'permohonan_surat_id' => 'required',
                    'nama_pen' => 'required',
                    'nik_pen' => 'required',
                    'tempat_lahir_pen' => 'required',
                    'tanggal_lahir_pen' => 'required',
                    'umur_pen' => 'required',
                    'alamat_pen' => 'required',
                    'desa_pen' => 'required',
                    'kecamatan_pen' => 'required',
                    'kabupaten_pen' => 'required',
                    'keperluan' => 'required',
                    'jenis_kelamin_pen' => 'required',
                    'pekerjaan_pen' => 'required',
                ]);
                try {
                    PermohonanSuratKuasa::create([
                        'permohonan_surat_id' => $permohonan_surat_id->id,
                        'nama_pem' => $warga->nama_warga,
                        'tempat_lahir_pem' => $warga->tempat_lahir,
                        'tanggal_lahir_pem' => $warga->tanggal_lahir,
                        'jenis_kelamin_pem' => $warga->jenis_kelamin,
                        'alamat_pem' => $warga->alamat,
                        'desa_pem' => $warga->desa->nama_desa,
                        'kecamatan_pem' => $warga->desa->kecamatan->nama_kecamatan,
                        'kabupaten_pem' => $warga->desa->kecamatan->kabupaten->nama_kabupaten,
                        'nama_pen' => $request->nama_pen,
                        'nik_pen' => $request->nik_pen,
                        'tempat_lahir_pen' => $request->tempat_lahir_pen,
                        'tanggal_lahir_pen' => $request->tanggal_lahir_pen,
                        'umur_pen' => $request->umur_pen,
                        'alamat_pen' => $request->alamat_pen,
                        'desa_pen' => $request->desa_pen,
                        'kecamatan_pen' => $request->kecamatan_pen,
                        'kabupaten_pen' => $request->kabupaten_pen,
                        'keperluan' => $request->keperluan,
                        'jenis_kelamin_pen' => $request->jenis_kelamin_pen,
                        'pekerjaan_pen' => $request->pekerjaan_pen,
                    ]);
                    $body = array(
                        'nama_pemberi_kuasa' => $warga->nama_warga,
                        'nik_pemberi_kuasa' => $warga->nik,
                        'tempat_lahir_pemberi_kuasa' => $warga->tempat_lahir,
                        'tanggal_lahir_pemberi_kuasa' => $warga->tanggal_lahir,
                        'umur_pemberi_kuasa' => $request->umur,
                        'jkpemberi_kuasa' => $warga->jenis_kelamin,
                        'pekerjaanpemberi_kuasa' => $warga->pekerjaan,
                        'alamat_pemberi_kuasa' => $warga->alamat,
                        'form_desapemberi_kuasa' => $warga->desa->nama_desa,
                        'form_kecpemberi_kuasa' => $warga->desa->kecamatan->nama_kecamatan,
                        'form_kabpemberi_kuasa' => $warga->desa->kecamatan->kabupaten->nama_kabupaten,
                        'nama_penerima_kuasa' => $request->nama_pen,
                        'nik_penerima_kuasa' => $request->nik_pen,
                        'tempat_lahir_penerima_kuasa' => $request->tempat_lahir_pen,
                        'tanggal_lahir_penerima_kuasa' => $request->tanggal_lahir_pen,
                        'umur_penerima_kuasa' => $request->umur_pen,
                        'jkpenerima_kuasa' => $request->jenis_kelamin_pen,
                        'pekerjaanpenerima_kuasa' => $request->pekerjaan_pen,
                        'alamat_penerima_kuasa' => $request->alamat_pen,
                        'form_desapenerima_kuasa' => $request->desa_pen,
                        'form_kecpenerima_kuasa' => $request->kecamatan_pen,
                        'form_kabpenerima_kuasa' => $request->kabupaten_pen,
                        'untuk_keperluan' => $request->keperluan
                    );
                    $array = array_merge(
                        $this->header($warga, [
                            'judul_surat' => 'Permohonan Surat Kuasa',
                            'nomor_surat' => '12312321'
                        ]),
                        $body,
                        $this->footer([
                            'kode_desa' => $warga->desa->id,
                            'kode_surat' => '1010110'
                        ])
                    );
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollBack();
                    Alert::error($th->getMessage());
                    return back();
                }
                break;
            case 6:
                $doc = '6_surat_ket_usaha';
                $this->validate($request, [
                    'permohonan_surat_id' => 'required',
                    'ktp' => 'required',
                    'kk' => 'required',
                    'pemegang_usaha' => 'required',
                    'usaha' => 'required',
                    'keterangan' => 'required',
                    'berlaku_mulai' => 'required',
                    'berlaku_sampai' => 'required',
                ]);
                try {
                    PermohonanSuratUsaha::create([
                        'permohonan_surat_id' => $permohonan_surat_id->id,
                        'ktp' => $request->ktp,
                        'kk' => $request->kk,
                        'pemegang_usaha' => $request->pemegang_usaha,
                        'usaha' => $request->usaha,
                        'keterangan' => $request->keterangan,
                        'berlaku_mulai' => $request->berlaku_mulai,
                        'berlaku_sampai' => $request->berlaku_sampai
                    ]);
                    $body = [
                        'nama' => $warga->nama_warga,
                        'no_ktp' => $warga->nik,
                        'no_kk' => $request->kk,
                        'kepala_kk' => $warga->nama_warga,
                        'ttl' => $warga->tempat_lahir . '/' . Carbon::parse($warga->tanggal_lahir)->format('d F Y'),
                        'usia' => Carbon::now()->format('Y') - Carbon::parse($warga->tanggal_lahir)->format('Y'),
                        'agama' => $warga->agama,
                        'sex' => $warga->jenis_kelamin,
                        'alamat' => $warga->desa->alamat,
                        'status' => $warga->status,
                        'pendidikan' => $warga->pendidikan,
                        'pekerjaan' => $warga->pekerjaan,
                        'warga_negara' => $warga->warga_negara,
                        'keperluan' => $request->keperluan,
                        'form_usaha' => $request->usaha,
                        'form_keterangan' => $request->keterangan,
                        'form_berlaku_dari' => $request->berlaku_mulai,
                        'form_berlaku_sampai' => $request->berlaku_sampai,
                        'penandatangan' => $warga->nama_warga,
                    ];
                    $array = array_merge(
                        $this->header($warga, [
                            'judul_surat' => 'Surat Keterangan Usaha',
                            'nomor_surat' => '12312321'
                        ]),
                        $body,
                        $this->footer([
                            'kode_desa' => $warga->desa->id,
                            'kode_surat' => '1010110'
                        ])
                    );
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollBack();
                    Alert::error($th->getMessage());
                    return back();
                }
                break;
            case 7:
                $doc = '7_surat_ket_domisili_usaha';
                $this->validate($request, [
                    'permohonan_surat_id' => 'required',
                    'usaha' => 'required',
                    'alamat_usaha' => 'required',
                ]);
                try {
                    PermohonanSuratDomisiliUsaha::create([
                        'permohonan_surat_id' => $permohonan_surat_id->id,
                        'usaha' => $request->usaha,
                        'alamat_usaha' => $request->alamat_usaha,
                    ]);
                    $body = [
                        'nama' => $warga->nama_warga,
                        'no_ktp' => $warga->nik,
                        'no_kk' => $request->kk,
                        'kepala_kk' => $warga->nama_warga,
                        'ttl' => $warga->tempat_lahir . '/' . Carbon::parse($warga->tanggal_lahir)->format('d F Y'),
                        'usia' => Carbon::now()->format('Y') - Carbon::parse($warga->tanggal_lahir)->format('Y'),
                        'agama' => $warga->agama,
                        'sex' => $warga->jenis_kelamin,
                        'alamat' => $warga->desa->alamat,
                        'status' => $warga->status,
                        'agama' => $warga->agama,
                        'pendidikan' => $warga->pendidikan,
                        'pekerjaan' => $warga->pekerjaan,
                        'warga_negara' => $warga->warga_negara,
                        'penandatangan' => auth()->user()->name,
                        'form_usaha' => $request->usaha
                    ];
                    $array = array_merge(
                        $this->header($warga, [
                            'judul_surat' => 'Surat Keterangan Usaha',
                            'nomor_surat' => '12312321'
                        ]),
                        $body,
                        $this->footer([
                            'kode_desa' => $warga->desa->id,
                            'kode_surat' => '1010110'
                        ])
                    );
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollBack();
                    Alert::error($th->getMessage());
                    return back();
                }

                break;
            case 8:
                $doc = '8_surat_ket_pergi_kawin';
                $this->validate($request, [
                    'permohonan_surat_id' => 'required',
                    'tujuan' => 'required',
                    'keperluan' => 'required',
                    'berlaku_mulai' => 'required',
                    'berlaku_sampai' => 'required',
                ]);
                try {
                    PermohonanSuratPergiKawin::create([
                        'permohonan_surat_id' => $permohonan_surat_id->id,
                        'tujuan' => $request->tujuan,
                        'keperluan' => $request->keperluan,
                        'berlaku_mulai' => $request->berlaku_mulai,
                        'berlaku_sampai' => $request->berlaku_sampai
                    ]);
                    $body = [
                        'nama' => $warga->nama_warga,
                        'no_ktp' => $warga->nik,
                        'no_kk' => $request->kk,
                        'kepala_kk' => $warga->nama_warga,
                        'ttl' => $warga->tempat_lahir . '/' . Carbon::parse($warga->tanggal_lahir)->format('d F Y'),
                        'usia' => Carbon::now()->format('Y') - Carbon::parse($warga->tanggal_lahir)->format('Y'),
                        'agama' => $warga->agama,
                        'sex' => $warga->jenis_kelamin,
                        'alamat' => $warga->desa->alamat,
                        'status' => $warga->status,
                        'agama' => $warga->agama,
                        'pendidikan' => $warga->pendidikan,
                        'pekerjaan' => $warga->pekerjaan,
                        'warga_negara' => $warga->warga_negara,
                        'penandatangan' => auth()->user()->name,
                        'form_tujuan' => $request->tujuan,
                        'form_keterangan' => $request->keperluan,
                        'form_berlaku_dari' => $request->berlaku_mulai,
                        'form_berlaku_sampai' => $request->berlaku_sampai
                    ];
                    $array = array_merge(
                        $this->header($warga, [
                            'judul_surat' => 'Surat Permohonan Pergi Kawin',
                            'nomor_surat' => '12312321'
                        ]),
                        $body,
                        $this->footer([
                            'kode_desa' => $warga->desa->id,
                            'kode_surat' => '1010110'
                        ])
                    );
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollBack();
                    Alert::error($th->getMessage());
                    return back();
                }
                break;
            case 9:
                $doc = '9_surat_ket_penghasilan_orangtua';

                $this->validate($request, [
                    'permohonan_surat_id' => 'required',
                    'no_induk' => 'required',
                    'sekolah' => 'required',
                    'orangtua_ayah_id' => 'required',
                    'orangtua_ibu_id' => 'required',
                    'kelas' => 'required',
                    'jurusan' => 'required',
                    'penghasilan_ayah' => 'required',
                    'penghasilan_ibu' => 'required',
                    'berlaku_mulai' => 'required',
                    'berlaku_sampai' => 'required'
                ]);
                $ayah = Warga::findOrFail($request->orangtua_ayah_id);
                $ibu = Warga::findOrFail($request->orangtua_ibu_id);
                $array_ayah = [
                    'd_nama_ayah' => $ayah->nama_warga,
                    'd_nik_ayah' => $ayah->nik,
                    'd_tempatlahir_ayah' => $ayah->tempat_lahir,
                    'd_tanggallahir_ayah' => Carbon::parse($ayah->tanggal_lahir)->format('d F Y'),
                    'd_agama_ayah' => $ayah->agama,
                    'd_pekerjaan_ayah' => $ayah->pekerjaan,
                    'hasil_ayah' => number_format($request->penghasilan_ayah)
                ];
                $array_ibu = [
                    'd_nama_ibu' => $ibu->nama_warga,
                    'd_nik_ibu' => $ibu->nik,
                    'd_tempatlahir_ibu' => $ibu->tempat_lahir,
                    'd_tanggallahir_ibu' => Carbon::parse($ibu->tanggal_lahir)->format('d F Y'),
                    'd_agama_ibu' => $ibu->agama,
                    'd_pekerjaan_ibu' => $ibu->pekerjaan,
                    'hasil_ibu' => number_format($request->penghasilan_ibu)
                ];
                try {
                    PermohonanSuratPenghasilan::create([
                        'permohonan_surat_id' => $permohonan_surat_id->id,
                        'orangtua_ayah_id' => $request->orangtua_ayah_id,
                        'orangtua_ibu_id' => $request->orangtua_ibu_id,
                        'no_induk' => $request->no_induk,
                        'sekolah' => $request->sekolah,
                        'kelas' => $request->kelas,
                        'jurusan' => $request->jurusan,
                        'penghasilan_ayah' => $request->penghasilan_ayah,
                        'penghasilan_ibu' => $request->penghasilan_ibu,
                        'berlaku_mulai' => $request->berlaku_mulai,
                        'berlaku_sampai' => $request->berlaku_sampai
                    ]);
                    $body = [
                        'nama' => $warga->nama_warga,
                        'no_ktp' => $warga->nik,
                        'no_kk' => $request->kk,
                        'kepala_kk' => $warga->nama_warga,
                        'tempat_tgl_lahir' => $warga->tempat_lahir . '/' . Carbon::parse($warga->tanggal_lahir)->format('d F Y'),
                        'usia' => Carbon::now()->format('Y') - Carbon::parse($warga->tanggal_lahir)->format('Y'),
                        'agama' => $warga->agama,
                        'sex' => $warga->jenis_kelamin,
                        'alamat' => $warga->desa->alamat,
                        'status' => $warga->status,
                        'agama' => $warga->agama,
                        'pendidikan' => $warga->pendidikan,
                        'pekerjaan' => $warga->pekerjaan,
                        'warga_negara' => $warga->warga_negara,
                        'penandatangan' => auth()->user()->name,
                        'nomor_induk' => $request->no_induk,
                        'jurusan' => $request->jurusan,
                        'sekolah_pt' => $request->sekolah,
                        'kelas_semester' => $request->kelas,
                        'form_tujuan' => $request->tujuan,
                        'form_keterangan' => $request->keperluan,
                        'form_berlaku_dari' => $request->berlaku_mulai,
                        'form_berlaku_sampai' => $request->berlaku_sampai,
                        'total_hasil' => number_format($request->penghasilan_ayah + $request->penghasilan_ibu),
                        'pamong' => auth()->user()->name,
                    ];
                    $array = array_merge(
                        $this->header($warga, [
                            'judul_surat' => 'Surat Permohonan Pergi Kawin',
                            'nomor_surat' => '12312321'
                        ]),
                        $body,
                        $array_ayah,
                        $array_ibu,
                        $this->footer([
                            'kode_desa' => $warga->desa->id,
                            'kode_surat' => '1010110'
                        ])
                    );
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollBack();
                    Alert::error($th->getMessage());
                    return back();
                }
                break;
            case 10:
                $doc = '10_surat_ket_jamkesos';
                $this->validate($request, [
                    'permohonan_surat_id' => 'required',
                    'no_jamkes' => 'required',
                    'keperluan' => 'required',
                ]);

                try {
                    PermohonanSuratJaminanKesehatan::create([
                        'permohonan_surat_id' => $permohonan_surat_id->id,
                        'no_jamkes' => $request->no_jamkes,
                        'keperluan' => $request->keperluan,
                    ]);
                    $body = [
                        'nama' => $warga->nama_warga,
                        'no_ktp' => $warga->nik,
                        'no_kk' => $request->kk,
                        'kepala_kk' => $warga->nama_warga,
                        'ttl' => $warga->tempat_lahir . '/' . Carbon::parse($warga->tanggal_lahir)->format('d F Y'),
                        'usia' => Carbon::now()->format('Y') - Carbon::parse($warga->tanggal_lahir)->format('Y'),
                        'agama' => $warga->agama,
                        'sex' => $warga->jenis_kelamin,
                        'alamat' => $warga->desa->alamat,
                        'status' => $warga->status,
                        'agama' => $warga->agama,
                        'pendidikan' => $warga->pendidikan,
                        'pekerjaan' => $warga->pekerjaan,
                        'warga_negara' => $warga->warga_negara,
                        'penandatangan' => auth()->user()->name,
                        'form_no_jamkesos' => $request->no_jamkes,
                        'form_keterangan' => $request->keperluan,
                    ];
                    $array = array_merge(
                        $this->header($warga, [
                            'judul_surat' => 'Permohonan Surat Jaminan Kesehatan',
                            'nomor_surat' => '12312321'
                        ]),
                        $body,
                        $this->footer([
                            'kode_desa' => $warga->desa->id,
                            'kode_surat' => '1010110'
                        ])
                    );
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollBack();
                    Alert::error($th->getMessage());
                    return back();
                }
                break;
            case 11:
                $doc = '11_surat_ket_kurang_mampu';
                $this->validate($request, [
                    'permohonan_surat_id' => 'required',
                    'keperluan' => 'required',
                ]);

                try {
                    $PermohonanSuratKurangMampu = PermohonanSuratKurangMampu::create([
                        'permohonan_surat_id' => $permohonan_surat_id->id,
                        'keperluan' => $request->keperluan,
                    ]);
                    $families = Warga::where('kk', $warga->kk)->get();
                    if (!DaftarKurangMampu::where('warga_id', $warga->id)->exists()) {
                        foreach ($families as $family) {
                            DaftarKurangMampu::create([
                                'surat_kurang_mampu_id' => $PermohonanSuratKurangMampu->id,
                                'warga_id' => $family->id
                            ]);
                        }
                    }
                    $body = [
                        'nama' => $warga->nama_warga,
                        'no_ktp' => $warga->nik,
                        'no_kk' => $request->kk,
                        'kepala_kk' => $warga->nama_warga,
                        'ttl' => $warga->tempat_lahir . '/' . Carbon::parse($warga->tanggal_lahir)->format('d F Y'),
                        'usia' => Carbon::now()->format('Y') - Carbon::parse($warga->tanggal_lahir)->format('Y'),
                        'agama' => $warga->agama,
                        'sex' => $warga->jenis_kelamin,
                        'alamat' => $warga->desa->alamat,
                        'status' => $warga->status,
                        'agama' => $warga->agama,
                        'pendidikan' => $warga->pendidikan,
                        'pekerjaan' => $warga->pekerjaan,
                        'warga_negara' => $warga->warga_negara,
                        'penandatangan' => auth()->user()->name,
                        'keperluan' => $request->keperluan
                    ];
                    
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollBack();
                    Alert::error($th->getMessage());
                    return back();
                }
                break;
            default:
                $doc = abort(404);
                break;
        }
        $file = public_path('template/' . $doc . '.docx');
        // dd($array);
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(public_path('template/' . $doc . '.docx'));

        $nama_file = $doc . '.docx';
        if ($doc == '11_surat_ket_kurang_mampu') {
            $fams = [];
            foreach($families as $key => $value ) {
                $key += 1;
                $fams['anggota_no_' . $key] = $value->id;
                $fams['anggota_nik_' . $key] = $value->nik;
                $fams['anggota_nama_' . $key] = $value->nama_warga;
                $fams['anggota_sex_' . $key] = $value->jenis_kelamin;
                $fams['anggota_tempatlahir_' . $key] = $value->tempat_lahir;
                $fams['anggota_tanggallahir_' . $key] = $value->tanggal_lahir;
                $fams['anggota_shdk_' . $key] = 'value';
            }
            $array = array_merge(
                $this->header($warga, [
                    'judul_surat' => 'Permohonan Surat Kurang Mampu',
                    'nomor_surat' => '12312321'
                ]),
                $body,
                $fams,
                $this->footer([
                    'kode_desa' => $warga->desa->id,
                    'kode_surat' => '1010110'
                ])
            );
        }
        try {
            // return \WordTemplate::export($file, $array, $nama_file);
            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($file);

            $templateProcessor->setValues($array);
            $templateProcessor->setImageValue('logo', array('path' => public_path('storage/' . Desa::find(auth()->user()->desa_id)->logo), 'width' => 50, 'height' => 50, 'ratio' => false));
            header("Content-Disposition: attachment; filename=" . $nama_file);

            $templateProcessor->saveAs('php://output');
        } catch (\Throwable $th) {
            dd($th->getMessage());
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
                    $table = 'permohonan_surat_penghasilans';
                    break;
                case 10:
                    $table = 'permohonan_surat_jaminan_kesehatans';
                    break;
                case 11:
                    $table = 'permohonan_surat_kurang_mampus';
                    break;
                case 12:
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
        PengajuanWarga::create([
            'permohonan_surat_id' => $id,
            'nama' => $warga->nama_warga,
            'nik' => $warga->nik,
            'tempat_lahir' => $warga->tempat_lahir,
            'jenis_kelamin' => $warga->jenis_kelamin,
            'alamat' => $warga->desa->alamat,
            'agama' => $warga->agama,
            'status_perkawinan' => $warga->status_pernikahan,
            'pekerjaan' => $warga->pekerjaan,
            'kewarganegaraan' => $warga->warga_negara,
            'golongan_darah' => $warga->golongan_darah
        ]);
        switch ($request->permohonan_surat_id) {
            case 1:
                $doc = '1_surat_ket_catatan_kriminal';
                $this->validate($request, [
                    'permohonan_surat_id' => 'required',
                    'keperluan' => 'required',
                    'pendidikan' => 'required',
                    'pekerjaan' => 'required',
                ]);
                try {
                    PermohonanSuratSkck::create([
                        'permohonan_surat_id' => $id,
                        'keperluan' => $request->keperluan,
                        'pendidikan' => $request->pendidikan,
                        'pekerjaan' => $request->pekerjaan,
                    ]);

                    $body = array(
                        'nama' => $warga->nama_warga,
                        'no_ktp' => $warga->nik,
                        'no_kk' => $warga->kk,
                        'kepala_kk' => $warga->nama_warga,
                        'ttl' => $warga->tempat_lahir . '/' . Carbon::parse($warga->tanggal_lahir)->format('d F Y'),
                        'agama' => $warga->agama,
                        'sex' => $warga->jenis_kelamin,
                        'alamat' => $warga->desa->alamat,
                        'status' => $warga->status,
                        'pendidikan' => $request->pendidikan,
                        'pekerjaan' => $request->pekerjaan,
                        'warga_negara' => $warga->warga_negara,
                        'form_keterangan' => $request->keperluan,
                        'penandatangan' => $warga->nama_warga,
                    );
                    $array = array_merge(
                        $this->header($warga, [
                            'judul_surat' => 'Surat Keterangan Catatan Kriminal',
                            'nomor_surat' => '1010110'
                        ]),
                        $body,
                        $this->footer([
                            'kode_desa' => $warga->desa->id,
                            'kode_surat' => '1010110'
                        ])
                    );

                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollBack();
                    Alert::error($th->getMessage());
                    return back();
                }
                break;
            case 2:
                $doc = '2_surat_ket_kehilangan';
                $this->validate($request, [
                    'permohonan_surat_id' => 'required',
                    'rincian_barang' => 'required',
                    'keterangan_hilang' => 'required',
                ]);
                try {
                    PermohonanSuratKehilangan::create([
                        'permohonan_surat_id' => $id,
                        'rincian_barang' => $request->rincian_barang,
                        'keterangan_hilang' => $request->keterangan_hilang
                    ]);
                    $body = array(
                        'nama' => $warga->nama_warga,
                        'no_ktp' => $warga->nik,
                        'no_kk' => $warga->kk,
                        'kepala_kk' => $warga->nama_warga,
                        'ttl' => $warga->tempat_lahir . '/' . Carbon::parse($warga->tanggal_lahir)->format('d F Y'),
                        'agama' => $warga->agama,
                        'sex' => $warga->jenis_kelamin,
                        'alamat' => $warga->desa->alamat,
                        'status' => $warga->status,
                        'pendidikan' => $warga->pendidikan,
                        'pekerjaan' => $warga->pekerjaan,
                        'warga_negara' => $warga->warga_negara,
                        'form_keterangan' => $request->keterangan_hilang,
                        'form_rincian' => $request->rincian_barang,
                        'penandatangan' => $warga->nama_warga,
                        'form_barang' => $request->rincian_barang
                    );
                    $array = array_merge(
                        $this->header($warga, [
                            'judul_surat' => 'Permohonan Surat Izin Keramaian',
                            'nomor_surat' => '12312321'
                        ]),
                        $body,
                        $this->footer([
                            'kode_desa' => $warga->desa->id,
                            'kode_surat' => '1010110'
                        ])
                    );
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollBack();
                    Alert::error($th->getMessage());
                    return back();
                }
                break;
            case 3:
                $doc = '3_surat_izin_keramaian';

                $this->validate($request, [
                    'permohonan_surat_id' => 'required',
                    'berlaku_mulai' => 'required',
                    'berlaku_sampai' => 'required',
                    'jenis_keramaian' => 'required',
                    'keperluan' => 'required',
                ]);
                try {
                    PermohonanSuratIzinKeramaian::create([
                        'permohonan_surat_id' => $id,
                        'berlaku_mulai' => $request->berlaku_mulai,
                        'berlaku_sampai' => $request->berlaku_sampai,
                        'jenis_keramaian' => $request->jenis_keramaian,
                        'keperluan' => $request->keperluan
                    ]);
                    $body = array(
                        'nama' => $warga->nama_warga,
                        'no_ktp' => $warga->nik,
                        'no_kk' => $warga->kk,
                        'kepala_kk' => $warga->nama_warga,
                        'ttl' => $warga->tempat_lahir . '/' . Carbon::parse($warga->tanggal_lahir)->format('d F Y'),
                        'agama' => $warga->agama,
                        'sex' => $warga->jenis_kelamin,
                        'alamat' => $warga->desa->alamat,
                        'status' => $warga->status,
                        'pendidikan' => $warga->pendidikan,
                        'pekerjaan' => $warga->pekerjaan,
                        'warga_negara' => $warga->warga_negara,
                        'form_berlaku_dari' => $request->berlaku_mulai,
                        'form_berlaku_sampai' => $request->berlaku_sampai,
                        'form_jenis_keramaian' => $request->jenis_keramaian,
                        'form_keperluan' => $request->keperluan,
                        'penandatangan' => $warga->nama_warga,
                    );
                    $array = array_merge(
                        $this->header($warga, [
                            'judul_surat' => 'Permohonan Surat Izin Keramaian',
                            'nomor_surat' => '12312321'
                        ]),
                        $body,
                        $this->footer([
                            'kode_desa' => $warga->desa->id,
                            'kode_surat' => '1010110'
                        ])
                    );
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollBack();
                    Alert::error($th->getMessage());
                    return back();
                }
                break;
            case 4:
                $doc = '4_surat_ket_pengantar';

                $this->validate($request, [
                    'permohonan_surat_id' => 'required',
                    'berlaku_mulai' => 'required',
                    'berlaku_sampai' => 'required',
                    'keperluan' => 'required',
                ]);
                try {


                    PermohonanSuratPengantar::create([
                        'permohonan_surat_id' => $id,
                        'berlaku_mulai' => $request->berlaku_mulai,
                        'berlaku_sampai' => $request->berlaku_sampai,
                        'keperluan' => $request->keperluan
                    ]);
                    $body = array(
                        'nama' => $warga->nama_warga,
                        'no_ktp' => $warga->nik,
                        'no_kk' => $warga->kk,
                        'kepala_kk' => $warga->nama_warga,
                        'ttl' => $warga->tempat_lahir . '/' . Carbon::parse($warga->tanggal_lahir)->format('d F Y'),
                        'usia' => Carbon::now()->format('Y') - Carbon::parse($warga->tanggal_lahir)->format('Y'),
                        'agama' => $warga->agama,
                        'sex' => $warga->jenis_kelamin,
                        'alamat' => $warga->desa->alamat,
                        'status' => $warga->status,
                        'pendidikan' => $warga->pendidikan,
                        'pekerjaan' => $warga->pekerjaan,
                        'warga_negara' => $warga->warga_negara,
                        'gol_darah' => 'tidak di ketahui',
                        'mulai_berlaku' => $request->berlaku_mulai,
                        'tgl_akhir' => $request->berlaku_sampai,
                        'keperluan' => $request->keperluan,
                        'penandatangan' => $warga->nama_warga,
                    );
                    $array = array_merge(
                        $this->header($warga, [
                            'judul_surat' => 'Permohonan Surat Izin Keramaian',
                            'nomor_surat' => '12312321'
                        ]),
                        $body,
                        $this->footer([
                            'kode_desa' => $warga->desa->id,
                            'kode_surat' => '1010110'
                        ])
                    );
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollBack();
                    Alert::error($th->getMessage());
                    return back();
                }
                break;
            case 5:
                $doc = '5_surat_kuasa';

                $this->validate($request, [
                    'permohonan_surat_id' => 'required',
                    'nama_pem' => 'required',
                    'tempat_lahir_pem' => 'required',
                    'tanggal_lahir_pem' => 'required',
                    'jenis_kelamin_pem' => 'required',
                    'alamat_pem' => 'required',
                    'desa_pem' => 'required',
                    'kecamatan_pem' => 'required',
                    'kabupaten_pem' => 'required',
                    'nama_pen' => 'required',
                    'nik_pen' => 'required',
                    'tempat_lahir_pen' => 'required',
                    'tanggal_lahir_pen' => 'required',
                    'umur_pen' => 'required',
                    'alamat_pen' => 'required',
                    'desa_pen' => 'required',
                    'kecamatan_pen' => 'required',
                    'kabupaten_pen' => 'required',
                    'keperluan' => 'required',
                    'jenis_kelamin_pen' => 'required',
                    'pekerjaan_pen' => 'required',
                ]);
                try {
                    PermohonanSuratKuasa::create([
                        'permohonan_surat_id' => $id,
                        'nama_pem' => $warga->nama_warga,
                        'tempat_lahir_pem' => $warga->tempat_lahir,
                        'tanggal_lahir_pem' => $warga->tanggal_lahir,
                        'jenis_kelamin_pem' => $warga->jenis_kelamin,
                        'alamat_pem' => $warga->alamat,
                        'desa_pem' => $warga->desa->nama_desa,
                        'kecamatan_pem' => $warga->desa->kecamatan->nama_kecamatan,
                        'kabupaten_pem' => $warga->desa->kecamatan->kabupaten->nama_kabupaten,
                        'nama_pen' => $request->nama_pen,
                        'nik_pen' => $request->nik_pen,
                        'tempat_lahir_pen' => $request->tempat_lahir_pen,
                        'tanggal_lahir_pen' => $request->tanggal_lahir_pen,
                        'umur_pen' => $request->umur_pen,
                        'alamat_pen' => $request->alamat_pen,
                        'desa_pen' => $request->desa_pen,
                        'kecamatan_pen' => $request->kecamatan_pen,
                        'kabupaten_pen' => $request->kabupaten_pen,
                        'keperluan' => $request->keperluan,
                        'jenis_kelamin_pen' => $request->jenis_kelamin_pen,
                        'pekerjaan_pen' => $request->pekerjaan_pen,
                    ]);
                    $body = array(
                        'nama_pemberi_kuasa' => $warga->nama_warga,
                        'nik_pemberi_kuasa' => $warga->nik,
                        'tempat_lahir_pemberi_kuasa' => $warga->tempat_lahir,
                        'tanggal_lahir_pemberi_kuasa' => $warga->tanggal_lahir,
                        'umur_pemberi_kuasa' => $request->umur,
                        'jkpemberi_kuasa' => $warga->jenis_kelamin,
                        'pekerjaanpemberi_kuasa' => $warga->pekerjaan,
                        'alamat_pemberi_kuasa' => $warga->alamat,
                        'form_desapemberi_kuasa' => $warga->desa->nama_desa,
                        'form_kecpemberi_kuasa' => $warga->desa->kecamatan->nama_kecamatan,
                        'form_kabpemberi_kuasa' => $warga->desa->kecamatan->kabupaten->nama_kabupaten,
                        'nama_penerima_kuasa' => $request->nama_pen,
                        'nik_penerima_kuasa' => $request->nik_pen,
                        'tempat_lahir_penerima_kuasa' => $request->tempat_lahir_pen,
                        'tanggal_lahir_penerima_kuasa' => $request->tanggal_lahir_pen,
                        'umur_penerima_kuasa' => $request->umur_pen,
                        'jkpenerima_kuasa' => $request->jenis_kelamin_pen,
                        'pekerjaanpenerima_kuasa' => $request->pekerjaan_pen,
                        'alamat_penerima_kuasa' => $request->alamat_pen,
                        'form_desapenerima_kuasa' => $request->desa_pen,
                        'form_kecpenerima_kuasa' => $request->kecamatan_pen,
                        'form_kabpenerima_kuasa' => $request->kabupaten_pen,
                        'untuk_keperluan' => $request->keperluan
                    );
                    $array = array_merge(
                        $this->header($warga, [
                            'judul_surat' => 'Permohonan Surat Kuasa',
                            'nomor_surat' => '12312321'
                        ]),
                        $body,
                        $this->footer([
                            'kode_desa' => $warga->desa->id,
                            'kode_surat' => '1010110'
                        ])
                    );
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollBack();
                    Alert::error($th->getMessage());
                    return back();
                }
                break;
            case 6:
                $doc = '6_surat_ket_usaha';
                $this->validate($request, [
                    'permohonan_surat_id' => 'required',
                    'ktp' => 'required',
                    'kk' => 'required',
                    'pemegang_usaha' => 'required',
                    'usaha' => 'required',
                    'keterangan' => 'required',
                    'berlaku_mulai' => 'required',
                    'berlaku_sampai' => 'required',
                ]);
                try {
                    PermohonanSuratUsaha::create([
                        'permohonan_surat_id' => $id,
                        'ktp' => $request->ktp,
                        'kk' => $request->kk,
                        'pemegang_usaha' => $request->pemegang_usaha,
                        'usaha' => $request->usaha,
                        'keterangan' => $request->keterangan,
                        'berlaku_mulai' => $request->berlaku_mulai,
                        'berlaku_sampai' => $request->berlaku_sampai
                    ]);
                    $body = [
                        'nama' => $warga->nama_warga,
                        'no_ktp' => $warga->nik,
                        'no_kk' => $request->kk,
                        'kepala_kk' => $warga->nama_warga,
                        'ttl' => $warga->tempat_lahir . '/' . Carbon::parse($warga->tanggal_lahir)->format('d F Y'),
                        'usia' => Carbon::now()->format('Y') - Carbon::parse($warga->tanggal_lahir)->format('Y'),
                        'agama' => $warga->agama,
                        'sex' => $warga->jenis_kelamin,
                        'alamat' => $warga->desa->alamat,
                        'status' => $warga->status,
                        'pendidikan' => $warga->pendidikan,
                        'pekerjaan' => $warga->pekerjaan,
                        'warga_negara' => $warga->warga_negara,
                        'keperluan' => $request->keperluan,
                        'form_usaha' => $request->usaha,
                        'form_keterangan' => $request->keterangan,
                        'form_berlaku_dari' => $request->berlaku_mulai,
                        'form_berlaku_sampai' => $request->berlaku_sampai,
                        'penandatangan' => $warga->nama_warga,
                    ];
                    $array = array_merge(
                        $this->header($warga, [
                            'judul_surat' => 'Surat Keterangan Usaha',
                            'nomor_surat' => '12312321'
                        ]),
                        $body,
                        $this->footer([
                            'kode_desa' => $warga->desa->id,
                            'kode_surat' => '1010110'
                        ])
                    );
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollBack();
                    Alert::error($th->getMessage());
                    return back();
                }
                break;
            case 7:
                $doc = '7_surat_ket_domisili_usaha';
                $this->validate($request, [
                    'permohonan_surat_id' => 'required',
                    'usaha' => 'required',
                    'alamat_usaha' => 'required',
                ]);
                try {
                    PermohonanSuratDomisiliUsaha::create([
                        'permohonan_surat_id' => $id,
                        'usaha' => $request->usaha,
                        'alamat_usaha' => $request->alamat_usaha,
                    ]);
                    $body = [
                        'nama' => $warga->nama_warga,
                        'no_ktp' => $warga->nik,
                        'no_kk' => $request->kk,
                        'kepala_kk' => $warga->nama_warga,
                        'ttl' => $warga->tempat_lahir . '/' . Carbon::parse($warga->tanggal_lahir)->format('d F Y'),
                        'usia' => Carbon::now()->format('Y') - Carbon::parse($warga->tanggal_lahir)->format('Y'),
                        'agama' => $warga->agama,
                        'sex' => $warga->jenis_kelamin,
                        'alamat' => $warga->desa->alamat,
                        'status' => $warga->status,
                        'agama' => $warga->agama,
                        'pendidikan' => $warga->pendidikan,
                        'pekerjaan' => $warga->pekerjaan,
                        'warga_negara' => $warga->warga_negara,
                        'penandatangan' => auth()->user()->name,
                        'form_usaha' => $request->usaha
                    ];
                    $array = array_merge(
                        $this->header($warga, [
                            'judul_surat' => 'Surat Keterangan Usaha',
                            'nomor_surat' => '12312321'
                        ]),
                        $body,
                        $this->footer([
                            'kode_desa' => $warga->desa->id,
                            'kode_surat' => '1010110'
                        ])
                    );
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollBack();
                    Alert::error($th->getMessage());
                    return back();
                }
                break;
            case 8:
                $doc = '8_surat_ket_pergi_kawin';
                $this->validate($request, [
                    'permohonan_surat_id' => 'required',
                    'tujuan' => 'required',
                    'keperluan' => 'required',
                    'berlaku_mulai' => 'required',
                    'berlaku_sampai' => 'required',
                ]);
                try {
                    PermohonanSuratPergiKawin::create([
                        'permohonan_surat_id' => $id,
                        'tujuan' => $request->tujuan,
                        'keperluan' => $request->keperluan,
                        'berlaku_mulai' => $request->berlaku_mulai,
                        'berlaku_sampai' => $request->berlaku_sampai
                    ]);

                    $body = [
                        'nama' => $warga->nama_warga,
                        'no_ktp' => $warga->nik,
                        'no_kk' => $request->kk,
                        'kepala_kk' => $warga->nama_warga,
                        'ttl' => $warga->tempat_lahir . '/' . Carbon::parse($warga->tanggal_lahir)->format('d F Y'),
                        'usia' => Carbon::now()->format('Y') - Carbon::parse($warga->tanggal_lahir)->format('Y'),
                        'agama' => $warga->agama,
                        'sex' => $warga->jenis_kelamin,
                        'alamat' => $warga->desa->alamat,
                        'status' => $warga->status,
                        'agama' => $warga->agama,
                        'pendidikan' => $warga->pendidikan,
                        'pekerjaan' => $warga->pekerjaan,
                        'warga_negara' => $warga->warga_negara,
                        'penandatangan' => auth()->user()->name,
                        'form_tujuan' => $request->tujuan,
                        'form_keterangan' => $request->keperluan,
                        'form_berlaku_dari' => $request->berlaku_mulai,
                        'form_berlaku_sampai' => $request->berlaku_sampai
                    ];
                    $array = array_merge(
                        $this->header($warga, [
                            'judul_surat' => 'Surat Permohonan Pergi Kawin',
                            'nomor_surat' => '12312321'
                        ]),
                        $body,
                        $this->footer([
                            'kode_desa' => $warga->desa->id,
                            'kode_surat' => '1010110'
                        ])
                    );
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollBack();
                    Alert::error($th->getMessage());
                    return back();
                }
                break;
            case 9:
                $doc = '9_surat_ket_penghasilan_orangtua';
                dd($request->all());
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
        $file = public_path('template/' . $doc . '.docx');
        // dd($array);
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(public_path('template/' . $doc . '.docx'));

        $nama_file = $doc . '.docx';
        // dd($file);
        try {
            // return \WordTemplate::export($file, $array, $nama_file);
            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($file);

            $templateProcessor->setValues($array);
            // dd(asset('storage/'.Desa::find(auth()->user()->desa_id)->logo));
            $templateProcessor->setImageValue('logo', array('path' => public_path('storage/' . Desa::find(auth()->user()->desa_id)->logo), 'width' => 50, 'height' => 50, 'ratio' => false));
            header("Content-Disposition: attachment; filename=" . $nama_file);

            $templateProcessor->saveAs('php://output');
        } catch (\Throwable $th) {
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
    public function header(Warga $warga, $surat)
    {
        return [
            'nama_kab' => $warga->desa->kecamatan->kabupaten->nama_kabupaten,
            'nama_kec' => $warga->desa->kecamatan->nama_kecamatan,
            'nama_des' => $warga->desa->nama_desa,
            'alamat_des' => $warga->desa->alamat,
            'nama_provinsi' => $warga->desa->kecamatan->kabupaten->provinsi->nama_provinsi,
            'judul_surat' => $surat['judul_surat'],
            'format_nomor_surat' => $surat['nomor_surat'],
            'Sebutan_desa' => 'desa',
            'Sebutan_kabupaten' => 'kabupaten',
            'Sebutan_Desa' => 'desa'
        ];
    }
    public function footer($surat)
    {
        return [
            'kode_desa' => $surat['kode_desa'],
            'kode_surat' => $surat['kode_surat'],
            'tgl_surat' => Carbon::now()->format('d m Y'),
            'nama_pamong' => auth()->user()->name,
            'pamong_nip' => auth()->user()->id,
            'jabatan' => auth()->user()->roles()->first()->name
        ];
    }
}
