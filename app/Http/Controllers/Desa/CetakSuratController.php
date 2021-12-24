<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\DaftarKurangMampu;
use App\Models\Desa;
use App\Models\JenisSurat;
use App\Models\PengajuanWarga;
use App\Models\PermohonanSurat;
use App\Models\PermohonanSuratBedaIdentitas;
use App\Models\PermohonanSuratDomisiliUsaha;
use App\Models\PermohonanSuratIzinKeramaian;
use App\Models\PermohonanSuratJalan;
use App\Models\PermohonanSuratJaminanKesehatan;
use App\Models\PermohonanSuratJualBeli;
use App\Models\PermohonanSuratKehilangan;
use App\Models\PermohonanSuratKelahiran;
use App\Models\PermohonanSuratKtp;
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
use phpDocumentor\Reflection\Types\Nullable;
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
                    ];
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollBack();
                    Alert::error($th->getMessage());
                    return back();
                }
                break;
            case 12:
                $doc = '12_surat_ket_ktp_dalam_proses';
                $this->validate($request, [
                    'permohonan_surat_id' => 'required',
                    'berlaku_mulai' => 'required',
                    'berlaku_sampai' => 'required',
                ]);

                try {
                    PermohonanSuratKtp::create([
                        'permohonan_surat_id' => $permohonan_surat_id->id,
                        'berlaku_mulai' => $request->berlaku_mulai,
                        'berlaku_sampai' => $request->berlaku_sampai,
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
                        'keperluan' => $request->keperluan
                    ];

                    $array = array_merge(
                        $this->header($warga, [
                            'judul_surat' => 'Permohonan Surat KTP Dalam Proses',
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
            case 13:
                $doc = '13_surat_ket_kelahiran';
                // dd($request->all());
                $this->validate($request, [
                    'permohonan_surat_id' => 'required',
                    'orangtua_ayah_id' => 'required',
                    'orangtua_ibu_id' => 'required',
                    'nama_anak' => 'required',
                    'jenis_kelamin_anak' => 'required',
                    'tanggal_lahir_anak' => 'required',
                    'pukul_lahir_anak' => 'required',
                    'tempat_lahir_anak' => 'required',
                    'hubungan_pelapor' => 'required',
                    'anak_ke' => 'required',
                ]);
                $ibu = Warga::findOrFail($request->orangtua_ibu_id);
                $ayah = Warga::findOrFail($request->orangtua_ayah_id);
                $pelapor = Warga::findOrFail($request->warga_id);

                $saksi_satu = Warga::find($request->saksi_satu_id);
                $saksi_dua = Warga::find($request->saksi_dua_id);
                try {
                    PermohonanSuratKelahiran::create([
                        'permohonan_surat_id' => $permohonan_surat_id->id,
                        'orangtua_ayah_id' => $request->orangtua_ayah_id,
                        'orangtua_ibu_id' => $request->orangtua_ibu_id,
                        'nama_anak' => $request->nama_anak,
                        'jenis_kelamin_anak' => $request->jenis_kelamin_anak,
                        'tanggal_lahir_anak' => $request->tanggal_lahir_anak,
                        'pukul_lahir_anak' => $request->pukul_lahir_anak,
                        'tempat_lahir_anak' => $request->tempat_lahir_anak,
                        'hubungan_pelapor' => $request->hubungan_pelapor,
                        'anak_ke' => $request->anak_ke,
                        'saksi_satu_id' => $request->saksi_satu_id,
                        'saksi_dua_id' => $request->saksi_dua_id,
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
                        'keperluan' => $request->keperluan,
                        'orangtua_ayah_id' => $request->orangtua_ayah_id,
                        'orangtua_ibu_id' => $request->orangtua_ibu_id,
                        'form_nama_bayi' => $request->nama_anak,
                        'form_nama_sex' => $request->jenis_kelamin_anak,
                        'form_hari' => Carbon::parse($request->tanggal_lahir_anak)->format('l'),
                        'form_tanggallahir' => Carbon::parse($request->tanggal_anak)->format('Y F d'),
                        'form_waktu_lahir' => $request->pukul_lahir_anak,
                        'form_tempatlahir' => $request->tempat_lahir_anak,
                        'form_kelahiran_anak_ke' => $request->anak_ke,
                        'form_hubungan_pelapor' => $request->hubungan_pelapor,
                        'form_nama_ibu' => $ibu->nama_warga,

                        'nik_ibu' => $ibu->nik,
                        'umur_ibu' => Carbon::now()->format('Y') - Carbon::parse($ibu->tanggal_lahir)->format('Y'),
                        'pekerjaanibu' => $ibu->pekerjaan,
                        'alamat_ibu' => $ibu->alamat,
                        'desaibu' => $ibu->desa->nama_desa,
                        'kecibu' => $ibu->desa->kecamatan->nama_kecamatan,
                        'kabibu' => $ibu->desa->kecamatan->kabupaten->nama_kabupaten,
                        'tempat_lahir_ibu' => $request->tempat_lahir,
                        'tanggal_lahir_ibu' => $request->tanggal_lahir,

                        'form_nama_ayah' => $ayah->nama_warga,
                        'nik_ayah' => $ayah->nik,
                        'umur_ayah' => Carbon::now()->format('Y') - Carbon::parse($ayah->tanggal_lahir)->format('Y'),
                        'pekerjaanayah' => $ayah->pekerjaan,
                        'alamat_ayah' => $ayah->alamat,
                        'desaayah' => $ayah->desa->nama_desa,
                        'kecayah' => $ayah->desa->kecamatan->nama_kecamatan,
                        'kabayah' => $ayah->desa->kecamatan->kabupaten->nama_kabupaten,

                        'form_nama_pelapor' => $pelapor->nama_warga,
                        'form_nik_pelapor' => $pelapor->nik,
                        'form_umur_pelapor' => Carbon::now()->format('Y') - Carbon::parse($pelapor->tanggal_lahir)->format('Y'),
                        'form_pekerjaanpelapor' => $pelapor->pekerjaan,
                        'form_desapelapor' => $pelapor->desa->nama_desa,
                        'form_kecpelapor' => $pelapor->desa->kecamatan->nama_kecamatan,
                        'form_kabpelapor' => $pelapor->desa->kecamatan->kabupaten->nama_kabupaten,
                        'form_provinsipelapor' => $pelapor->desa->kecamatan->kabupaten->provinsi->nama_provinsi,
                        'lokasi_disdukcapil' => $pelapor->desa->alamat,

                        'nama_pelapor' => $pelapor->nama_warga,
                        'nik_pelapor' => $pelapor->nik,
                        'umur_pelapor' => Carbon::now()->format('Y') - Carbon::parse($pelapor->tanggal_lahir)->format('Y'),
                        'pekerjaanpelapor' => $pelapor->pekerjaan,
                        'tempat_lahir_pelapor' => $pelapor->tempat_lahir,
                        'tanggal_lahir_pelapor' => Carbon::parse($pelapor->tanggal_lahir)->format('d F Y'),
                        'desapelapor' => $pelapor->desa->nama_desa,
                        'kecpelapor' => $pelapor->desa->kecamatan->nama_kecamatan,
                        'kabpelapor' => $pelapor->desa->kecamatan->kabupaten->nama_kabupaten,
                        'provinsipelapor' => $pelapor->desa->kecamatan->kabupaten->provinsi->nama_provinsi,
                        'Sebutan_Kecamatan' => 'Kecamatan',
                        'Sebutan_Kabupaten' => 'Kabupaten',

                        'nama_saksi1' => $saksi_satu->nama_warga ?? 'Kosong',
                        'nik_saksi1' => $saksi_satu->nik ?? 'Kosong',
                        'tempat_lahir_saksi1' => $saksi_satu->tempat_lahir ?? 'Kosong',
                        'tanggal_lahir_saksi1' => $saksi_satu->tanggal_lahir ?? 'Kosong',
                        'umur_saksi1' => Carbon::now()->format('Y') - Carbon::parse($saksi_satu->tanggal_lahir ?? Carbon::now()->format('Y'))->format('Y'),
                        'pekerjaansaksi1' => $saksi_satu->pekerjaan ?? 'Kosong',
                        'form_desasaksi1' => $saksi_satu->desa->nama_desa ?? 'Kosong',
                        'form_kecsaksi1' => $saksi_satu->desa->kecamatan->nama_kecamatan ?? 'Kosong',
                        'form_kabsaksi1' => $saksi_satu->desa->kecamatan->kabupaten->nama_kabupaten ?? 'Kosong',
                        'form_provinsisaksi1' => $saksi_satu->desa->kecamatan->kabupaten->provinsi->nama_provinvsi ?? 'Kosong',

                        'nama_saksi2' => $saksi_dua->nama_warga ?? 'Kosong',
                        'nik_saksi2' => $saksi_dua->nik ?? 'Kosong',
                        'tempat_lahir_saksi2' => $saksi_dua->tempat_lahir ?? 'Kosong',
                        'tanggal_lahir_saksi2' => $saksi_dua->tanggal_lahir ?? 'Kosong',
                        'umur_saksi2' => Carbon::now()->format('Y') - Carbon::parse($saksi_dua->tanggal_lahir ?? Carbon::now()->format('Y'))->format('Y'),
                        'pekerjaansaksi2' => $saksi_dua->pekerjaan ?? 'Kosong',
                        'form_desasaksi2' => $saksi_dua->desa->nama_desa ?? 'Kosong',
                        'form_kecsaksi2' => $saksi_dua->desa->kecamatan->nama_kecamatan ?? 'Kosong',
                        'form_kabsaksi2' => $saksi_dua->desa->kecamatan->kabupaten->nama_kabupaten ?? 'Kosong',
                        'form_provinsisaksi2' => $saksi_dua->desa->kecamatan->kabupaten->provinsi->nama_provinvsi ?? 'Kosong',
                    ];

                    $array = array_merge(
                        $this->header($warga, [
                            'judul_surat' => 'Permohonan Surat Kelahiran',
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
            case 14:

                $doc = '14_surat_ket_jual_beli';
                $this->validate($request, [
                    'permohonan_surat_id' => 'required',
                    'pihak_id' => 'required',
                    'nama_barang' => 'required',
                    'jenis_barang' => 'required',
                    'keterangan' => 'required'
                ]);
                $pihak = Warga::findOrFail($request->pihak_id);
                try {
                    PermohonanSuratJualBeli::create([
                        'permohonan_surat_id' => $permohonan_surat_id->id,
                        'pihak_id' => $request->pihak_id,
                        'nama_barang' => $request->nama_barang,
                        'jenis_barang' => $request->jenis_barang,
                        'keterangan' => $request->keterangan
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
                        'form_barang' => $request->nama_barang,
                        'form_jenis' => $request->jenis_barang,
                        'form_nama' => $pihak->nama_warga,
                        'form_identitas' => $pihak->nik,
                        'form_tempatlahir' => $pihak->tempat_lahir,
                        'form_tanggallahir' => $pihak->tanggal_lahir,
                        'form_sex' => $pihak->jenis_kelamin,
                        'form_alamat' => $pihak->alamat,
                        'form_pekerjaan' => $pihak->pekerjaan,
                        'form_keterangan' => $request->keterangan,
                        'form_ketua_adat' => auth()->user()->desa->nama_desa
                    ];

                    $array = array_merge(
                        $this->header($warga, [
                            'judul_surat' => 'Permohonan Surat Jual Beli',
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
            case 15:
                abort(404);
                break;
            case 16:
                $doc = '16_surat_ket_beda_nama';
                try {
                    PermohonanSuratBedaIdentitas::create([
                        'permohonan_surat_id' => $permohonan_surat_id->id,
                        'no_identitas' => $request->no_identitas,
                        'nama_identitas' => $request->nama_identitas,
                        'tempat_lahir_identitas' => $request->tempat_lahir_identitas,
                        'tanggal_lahir_identitas' => $request->tanggal_lahir_identitas,
                        'jenis_kelamin_identitas' => $request->jenis_kelamin_identitas,
                        'alamat_identitas' => $request->alamat_identitas,
                        'agama_identitas' => $request->agama_identitas,
                        'pekerjaan_identitas' => $request->pekerjaan_identitas,
                        'keterangan_identitas' => $request->keterangan_identitas,
                        'perbedaan' => $request->perbedaan,
                    ]);
                    $body = [
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
                        'agama' => $warga->agama,
                        'pendidikan' => $warga->pendidikan,
                        'pekerjaan' => $warga->pekerjaan,
                        'warga_negara' => $warga->warga_negara,
                        'form_identitas' => $request->no_identitas,
                        'form_nama' => $request->nama_identitas,
                        'form_tempatlahir' => $request->tempat_lahir_identitas,
                        'form_tanggallahir' => $request->tanggal_lahir_identitas,
                        'form_sex' => $request->jenis_kelamin_identitas,
                        'form_alamat' => $request->alamat_identitas,
                        'form_pekerjaan' => $request->pekerjaan_identitas,
                        'form_keterangan' => $request->keterangan_identitas,
                        'form_perbedaan' => $request->perbedaan_identitas,
                        'form_agama' => $request->agama_identitas,
                        'penandatangan' => auth()->user()->name,
                    ];

                    $array = array_merge(
                        $this->header($warga, [
                            'judul_surat' => 'Permohonan Surat Ganti Identitas',
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
                    dd($th->getMessage());
                    DB::rollBack();
                    Alert::error($th->getMessage());
                    return back();
                }
                break;
            case 17:

                $doc = '17_surat_jalan';
                $this->validate($request, [
                    'keterangan' => 'required',
                    'berlaku_mulai' => 'required',
                    'berlaku_sampai' => 'required',
                ]);
                try {
                    PermohonanSuratJalan::create([
                        'permohonan_surat_id' => $permohonan_surat_id->id,
                        'keterangan' => $request->keterangan,
                        'berlaku_mulai' => $request->berlaku_mulai,
                        'berlaku_sampai' => $request->berlaku_sampai,
                    ]);
                    $body = [
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
                        'agama' => $warga->agama,
                        'pendidikan' => $warga->pendidikan,
                        'pekerjaan' => $warga->pekerjaan,
                        'warga_negara' => $warga->warga_negara,
                        'penandatangan' => auth()->user()->name,
                        'form_keterangan' => $request->keterangan,
                        'mulai_berlaku' => $request->berlaku_mulai,
                        'tgl_akhir' => $request->berlaku_sampai,
                    ];

                    $array = array_merge(
                        $this->header($warga, [
                            'judul_surat' => 'Permohonan Surat Jalan',
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
                    dd($th->getMessage());
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
            foreach ($families as $key => $value) {
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
            header('Content-Disposition: attachment; filename=' . $nama_file);

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
                    $table = 'permohonan_surat_ktps';
                    break;
                case 13:
                    $table = 'permohonan_surat_kelahirans';
                    break;
                case 14:
                    $table = 'permohonan_surat_jual_belis';
                    break;
                case 15:
                    abort(404);
                    break;
                case 16:
                    $table = 'permohonan_surat_beda_identitas';
                    break;
                case 17:
                    $table = 'permohonan_surat_jalans';
                    break;
                default:
                    abort(404);
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
                case 12:
                    $table = 'permohonan_surat_ktps';
                    break;
                case 13:
                    $table = 'permohonan_surat_kelahirans';
                    break;
                case 14:
                    $table = 'permohonan_surat_jual_belis';
                    break;
                case 15:
                    abort(404);
                    break;
                case 16:
                    $table = 'permohonan_surat_beda_identitas';
                    break;
                case 17:
                    $table = 'permohonan_surat_jalans';
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
                        'permohonan_surat_id' => $id,
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
                        'permohonan_surat_id' => $id,
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
                        'permohonan_surat_id' => $id,
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
                    ];
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollBack();
                    Alert::error($th->getMessage());
                    return back();
                }
                break;
            case 12:
                $doc = '12_surat_ket_ktp_dalam_proses';
                $this->validate($request, [
                    'permohonan_surat_id' => 'required',
                    'berlaku_mulai' => 'required',
                    'berlaku_sampai' => 'required',
                ]);

                try {
                    PermohonanSuratKtp::create([
                        'permohonan_surat_id' => $id,
                        'berlaku_mulai' => $request->berlaku_mulai,
                        'berlaku_sampai' => $request->berlaku_sampai,
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
                        'keperluan' => $request->keperluan
                    ];

                    $array = array_merge(
                        $this->header($warga, [
                            'judul_surat' => 'Permohonan Surat KTP Dalam Proses',
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
            case 13:
                $doc = '13_surat_ket_kelahiran';
                // dd($request->all());
                $this->validate($request, [
                    'permohonan_surat_id' => 'required',
                    'orangtua_ayah_id' => 'required',
                    'orangtua_ibu_id' => 'required',
                    'nama_anak' => 'required',
                    'jenis_kelamin_anak' => 'required',
                    'tanggal_lahir_anak' => 'required',
                    'pukul_lahir_anak' => 'required',
                    'tempat_lahir_anak' => 'required',
                    'hubungan_pelapor' => 'required',
                    'anak_ke' => 'required',
                ]);
                $ibu = Warga::findOrFail($request->orangtua_ibu_id);
                $ayah = Warga::findOrFail($request->orangtua_ayah_id);
                $pelapor = Warga::findOrFail($request->warga_id);

                $saksi_satu = Warga::find($request->saksi_satu_id);
                $saksi_dua = Warga::find($request->saksi_dua_id);
                try {
                    PermohonanSuratKelahiran::create([
                        'permohonan_surat_id' => $id,
                        'orangtua_ayah_id' => $request->orangtua_ayah_id,
                        'orangtua_ibu_id' => $request->orangtua_ibu_id,
                        'nama_anak' => $request->nama_anak,
                        'jenis_kelamin_anak' => $request->jenis_kelamin_anak,
                        'tanggal_lahir_anak' => $request->tanggal_lahir_anak,
                        'pukul_lahir_anak' => $request->pukul_lahir_anak,
                        'tempat_lahir_anak' => $request->tempat_lahir_anak,
                        'hubungan_pelapor' => $request->hubungan_pelapor,
                        'anak_ke' => $request->anak_ke,
                        'saksi_satu_id' => $request->saksi_satu_id,
                        'saksi_dua_id' => $request->saksi_dua_id,
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
                        'keperluan' => $request->keperluan,
                        'orangtua_ayah_id' => $request->orangtua_ayah_id,
                        'orangtua_ibu_id' => $request->orangtua_ibu_id,
                        'form_nama_bayi' => $request->nama_anak,
                        'form_nama_sex' => $request->jenis_kelamin_anak,
                        'form_hari' => Carbon::parse($request->tanggal_lahir_anak)->format('l'),
                        'form_tanggallahir' => Carbon::parse($request->tanggal_anak)->format('Y F d'),
                        'form_waktu_lahir' => $request->pukul_lahir_anak,
                        'form_tempatlahir' => $request->tempat_lahir_anak,
                        'form_kelahiran_anak_ke' => $request->anak_ke,
                        'form_hubungan_pelapor' => $request->hubungan_pelapor,
                        'form_nama_ibu' => $ibu->nama_warga,

                        'nik_ibu' => $ibu->nik,
                        'umur_ibu' => Carbon::now()->format('Y') - Carbon::parse($ibu->tanggal_lahir)->format('Y'),
                        'pekerjaanibu' => $ibu->pekerjaan,
                        'alamat_ibu' => $ibu->alamat,
                        'desaibu' => $ibu->desa->nama_desa,
                        'kecibu' => $ibu->desa->kecamatan->nama_kecamatan,
                        'kabibu' => $ibu->desa->kecamatan->kabupaten->nama_kabupaten,
                        'tempat_lahir_ibu' => $request->tempat_lahir,
                        'tanggal_lahir_ibu' => $request->tanggal_lahir,

                        'form_nama_ayah' => $ayah->nama_warga,
                        'nik_ayah' => $ayah->nik,
                        'umur_ayah' => Carbon::now()->format('Y') - Carbon::parse($ayah->tanggal_lahir)->format('Y'),
                        'pekerjaanayah' => $ayah->pekerjaan,
                        'alamat_ayah' => $ayah->alamat,
                        'desaayah' => $ayah->desa->nama_desa,
                        'kecayah' => $ayah->desa->kecamatan->nama_kecamatan,
                        'kabayah' => $ayah->desa->kecamatan->kabupaten->nama_kabupaten,

                        'form_nama_pelapor' => $pelapor->nama_warga,
                        'form_nik_pelapor' => $pelapor->nik,
                        'form_umur_pelapor' => Carbon::now()->format('Y') - Carbon::parse($pelapor->tanggal_lahir)->format('Y'),
                        'form_pekerjaanpelapor' => $pelapor->pekerjaan,
                        'form_desapelapor' => $pelapor->desa->nama_desa,
                        'form_kecpelapor' => $pelapor->desa->kecamatan->nama_kecamatan,
                        'form_kabpelapor' => $pelapor->desa->kecamatan->kabupaten->nama_kabupaten,
                        'form_provinsipelapor' => $pelapor->desa->kecamatan->kabupaten->provinsi->nama_provinsi,
                        'lokasi_disdukcapil' => $pelapor->desa->alamat,

                        'nama_pelapor' => $pelapor->nama_warga,
                        'nik_pelapor' => $pelapor->nik,
                        'umur_pelapor' => Carbon::now()->format('Y') - Carbon::parse($pelapor->tanggal_lahir)->format('Y'),
                        'pekerjaanpelapor' => $pelapor->pekerjaan,
                        'tempat_lahir_pelapor' => $pelapor->tempat_lahir,
                        'tanggal_lahir_pelapor' => Carbon::parse($pelapor->tanggal_lahir)->format('d F Y'),
                        'desapelapor' => $pelapor->desa->nama_desa,
                        'kecpelapor' => $pelapor->desa->kecamatan->nama_kecamatan,
                        'kabpelapor' => $pelapor->desa->kecamatan->kabupaten->nama_kabupaten,
                        'provinsipelapor' => $pelapor->desa->kecamatan->kabupaten->provinsi->nama_provinsi,
                        'Sebutan_Kecamatan' => 'Kecamatan',
                        'Sebutan_Kabupaten' => 'Kabupaten',

                        'nama_saksi1' => $saksi_satu->nama_warga ?? 'Kosong',
                        'nik_saksi1' => $saksi_satu->nik ?? 'Kosong',
                        'tempat_lahir_saksi1' => $saksi_satu->tempat_lahir ?? 'Kosong',
                        'tanggal_lahir_saksi1' => $saksi_satu->tanggal_lahir ?? 'Kosong',
                        'umur_saksi1' => Carbon::now()->format('Y') - Carbon::parse($saksi_satu->tanggal_lahir ?? Carbon::now()->format('Y'))->format('Y'),
                        'pekerjaansaksi1' => $saksi_satu->pekerjaan ?? 'Kosong',
                        'form_desasaksi1' => $saksi_satu->desa->nama_desa ?? 'Kosong',
                        'form_kecsaksi1' => $saksi_satu->desa->kecamatan->nama_kecamatan ?? 'Kosong',
                        'form_kabsaksi1' => $saksi_satu->desa->kecamatan->kabupaten->nama_kabupaten ?? 'Kosong',
                        'form_provinsisaksi1' => $saksi_satu->desa->kecamatan->kabupaten->provinsi->nama_provinvsi ?? 'Kosong',

                        'nama_saksi2' => $saksi_dua->nama_warga ?? 'Kosong',
                        'nik_saksi2' => $saksi_dua->nik ?? 'Kosong',
                        'tempat_lahir_saksi2' => $saksi_dua->tempat_lahir ?? 'Kosong',
                        'tanggal_lahir_saksi2' => $saksi_dua->tanggal_lahir ?? 'Kosong',
                        'umur_saksi2' => Carbon::now()->format('Y') - Carbon::parse($saksi_dua->tanggal_lahir ?? Carbon::now()->format('Y'))->format('Y'),
                        'pekerjaansaksi2' => $saksi_dua->pekerjaan ?? 'Kosong',
                        'form_desasaksi2' => $saksi_dua->desa->nama_desa ?? 'Kosong',
                        'form_kecsaksi2' => $saksi_dua->desa->kecamatan->nama_kecamatan ?? 'Kosong',
                        'form_kabsaksi2' => $saksi_dua->desa->kecamatan->kabupaten->nama_kabupaten ?? 'Kosong',
                        'form_provinsisaksi2' => $saksi_dua->desa->kecamatan->kabupaten->provinsi->nama_provinvsi ?? 'Kosong',
                    ];

                    $array = array_merge(
                        $this->header($warga, [
                            'judul_surat' => 'Permohonan Surat Kelahiran',
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
            case 14:

                $doc = '14_surat_ket_jual_beli';
                $this->validate($request, [
                    'permohonan_surat_id' => 'required',
                    'pihak_id' => 'required',
                    'nama_barang' => 'required',
                    'jenis_barang' => 'required',
                    'keterangan' => 'required'
                ]);
                $pihak = Warga::findOrFail($request->pihak_id);
                try {
                    PermohonanSuratJualBeli::create([
                        'permohonan_surat_id' => $id,
                        'pihak_id' => $request->pihak_id,
                        'nama_barang' => $request->nama_barang,
                        'jenis_barang' => $request->jenis_barang,
                        'keterangan' => $request->keterangan
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
                        'form_barang' => $request->nama_barang,
                        'form_jenis' => $request->jenis_barang,
                        'form_nama' => $pihak->nama_warga,
                        'form_identitas' => $pihak->nik,
                        'form_tempatlahir' => $pihak->tempat_lahir,
                        'form_tanggallahir' => $pihak->tanggal_lahir,
                        'form_sex' => $pihak->jenis_kelamin,
                        'form_alamat' => $pihak->alamat,
                        'form_pekerjaan' => $pihak->pekerjaan,
                        'form_keterangan' => $request->keterangan,
                        'form_ketua_adat' => auth()->user()->desa->nama_desa
                    ];

                    $array = array_merge(
                        $this->header($warga, [
                            'judul_surat' => 'Permohonan Surat Jual Beli',
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
            case 15:
                abort(404);
                break;
            case 16:
                $doc = '16_surat_ket_beda_nama';
                try {
                    PermohonanSuratBedaIdentitas::create([
                        'permohonan_surat_id' => $id,
                        'no_identitas' => $request->no_identitas,
                        'nama_identitas' => $request->nama_identitas,
                        'tempat_lahir_identitas' => $request->tempat_lahir_identitas,
                        'tanggal_lahir_identitas' => $request->tanggal_lahir_identitas,
                        'jenis_kelamin_identitas' => $request->jenis_kelamin_identitas,
                        'alamat_identitas' => $request->alamat_identitas,
                        'agama_identitas' => $request->agama_identitas,
                        'pekerjaan_identitas' => $request->pekerjaan_identitas,
                        'keterangan_identitas' => $request->keterangan_identitas,
                        'perbedaan' => $request->perbedaan,
                    ]);
                    $body = [
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
                        'agama' => $warga->agama,
                        'pendidikan' => $warga->pendidikan,
                        'pekerjaan' => $warga->pekerjaan,
                        'warga_negara' => $warga->warga_negara,
                        'form_identitas' => $request->no_identitas,
                        'form_nama' => $request->nama_identitas,
                        'form_tempatlahir' => $request->tempat_lahir_identitas,
                        'form_tanggallahir' => $request->tanggal_lahir_identitas,
                        'form_sex' => $request->jenis_kelamin_identitas,
                        'form_alamat' => $request->alamat_identitas,
                        'form_pekerjaan' => $request->pekerjaan_identitas,
                        'form_keterangan' => $request->keterangan_identitas,
                        'form_perbedaan' => $request->perbedaan_identitas,
                        'form_agama' => $request->agama_identitas,
                        'penandatangan' => auth()->user()->name,
                    ];

                    $array = array_merge(
                        $this->header($warga, [
                            'judul_surat' => 'Permohonan Surat Ganti Identitas',
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
                    dd($th->getMessage());
                    DB::rollBack();
                    Alert::error($th->getMessage());
                    return back();
                }
                break;
            case 17:

                $doc = '17_surat_jalan';
                $this->validate($request, [
                    'keterangan' => 'required',
                    'berlaku_mulai' => 'required',
                    'berlaku_sampai' => 'required',
                ]);
                try {
                    PermohonanSuratJalan::create([
                        'permohonan_surat_id' => $id,
                        'keterangan' => $request->keterangan,
                        'berlaku_mulai' => $request->berlaku_mulai,
                        'berlaku_sampai' => $request->berlaku_sampai,
                    ]);
                    $body = [
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
                        'agama' => $warga->agama,
                        'pendidikan' => $warga->pendidikan,
                        'pekerjaan' => $warga->pekerjaan,
                        'warga_negara' => $warga->warga_negara,
                        'penandatangan' => auth()->user()->name,
                        'form_keterangan' => $request->keterangan,
                        'mulai_berlaku' => $request->berlaku_mulai,
                        'tgl_akhir' => $request->berlaku_sampai,
                    ];

                    $array = array_merge(
                        $this->header($warga, [
                            'judul_surat' => 'Permohonan Surat Jalan',
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
                    dd($th->getMessage());
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
        // dd($file);

        if ($doc == '11_surat_ket_kurang_mampu') {
            $fams = [];
            foreach ($families as $key => $value) {
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
            // dd(asset('storage/'.Desa::find(auth()->user()->desa_id)->logo));
            $templateProcessor->setImageValue('logo', array('path' => public_path('storage/' . Desa::find(auth()->user()->desa_id)->logo), 'width' => 100, 'height' => 100, 'ratio' => false));
            header('Content-Disposition: attachment; filename=' . $nama_file);

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
            'Sebutan_Desa' => 'desa',
            'SEBUTAN_DESA' => 'Desa',
            'SEBUTAN_KABUPATEN' => 'Kabupaten'
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
