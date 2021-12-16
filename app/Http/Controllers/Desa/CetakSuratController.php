<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\JenisSurat;
use App\Models\PengajuanWarga;
use App\Models\PermohonanSurat;
use App\Models\PermohonanSuratIzinKeramaian;
use App\Models\PermohonanSuratKehilangan;
use App\Models\PermohonanSuratKuasa;
use App\Models\PermohonanSuratPengantar;
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
    public function header(Warga $warga, $surat)
    {
        return [
            '[nama_kab]' => $warga->desa->kecamatan->kabupaten->nama_kabupaten,
            '[nama_kec]' => $warga->desa->kecamatan->nama_kecamatan,
            '[nama_des]' => $warga->desa->nama_desa,
            '[alamat_des]' => $warga->desa->alamat,
            '[nama_provinsi]' => $warga->desa->kecamatan->kabupaten->provinsi->nama_provinsi,
            '[judul_surat]' => $surat['judul_surat'],
            '[format_nomor_surat]' => $surat['nomor_surat'],
            '[sebutan_kabupaten]' => 'Kabupaten Tenggek',
        ];
    }
    public function footer($surat)
    {
        return [
            '[kode_desa]' => $surat['kode_desa'],
            '[kode_surat]' => $surat['kode_surat'],
            '[tgl_surat]' => Carbon::now()->format('d m Y'),
            '[nama_pamong]' => auth()->user()->name,
            '[pamong_nip]' => auth()->user()->id,
            '[jabatan]' => auth()->user()->roles()->first()->name
        ];
    }
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
                $array = array_merge($this->header($warga, [
                    'judul_surat' => 'surat keterangan catatan kriminal',
                    'nomor_surat' => '1010110'
                ]));
                dd($array);
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
                    $doc = '1_surat_ket_catatan_kriminal';
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

                    $body = array(
                        '[nama]' => $warga->nama_warga,
                        '[no_ktp]' => $warga->nik,
                        '[no_kk]' => 'kk',
                        '[kepala_kk]' => $warga->nama_warga,
                        '[ttl]' => $warga->tempat_lahir . '/' . Carbon::parse($warga->tanggal_lahir)->format('d F Y'),
                        '[agama]' => 'islam',
                        '[sex]' => $warga->jenis_kelamin,
                        '[alamat]' => $warga->desa->alamat,
                        '[status]' => 'status',
                        '[pendidikan]' => $request->pendidikan,
                        '[pekerjaan]' => $request->pekerjaan,
                        '[warga_negara]' => 'Indonesia',
                        '[form_keterangan]' => $request->keperluan,
                        '[Sebutan_desa]' => 'Desa',
                        '[penandatangan]' => $warga->nama_warga,
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
                    break;
                case 2:
                    $doc = '2_surat_ket_kehilangan';
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
                    PermohonanSuratKehilangan::create([
                        'permohonan_surat_id' => $id,
                        'rincian_barang' => $request->rincian_barang,
                        'keterangan_hilang' => $request->keterangan_hilang
                    ]);
                    $body = array(
                        '[nama]' => $warga->nama_warga,
                        '[no_ktp]' => $warga->nik,
                        '[no_kk]' => 'kk',
                        '[kepala_kk]' => $warga->nama_warga,
                        '[ttl]' => $warga->tempat_lahir . '/' . Carbon::parse($warga->tanggal_lahir)->format('d F Y'),
                        '[agama]' => 'islam',
                        '[sex]' => $warga->jenis_kelamin,
                        '[alamat]' => $warga->desa->alamat,
                        '[status]' => 'status',
                        '[pendidikan]' => 'pendidikan',
                        '[pekerjaan]' => 'pekerjaaan',
                        '[warga_negara]' => 'Indonesia',
                        '[form_keterangan]' => $request->keterangan_hilang,
                        '[form_rincian]' => $request->rincian_barang,
                        '[penandatangan]' => $warga->nama_warga,
                        '[form_barang]' => $request->rincian_barang
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
                    break;
                case 3:
                    $doc = '3_surat_izin_keramaian';
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
                    PermohonanSuratIzinKeramaian::create([
                        'permohonan_surat_id' => $id,
                        'berlaku_mulai' => $request->berlaku_mulai,
                        'berlaku_sampai' => $request->berlaku_sampai,
                        'jenis_keramaian' => $request->jenis_keramaian,
                        'keperluan' => $request->keperluan
                    ]);
                    $body = array(
                        '[nama]' => $warga->nama_warga,
                        '[no_ktp]' => $warga->nik,
                        '[no_kk]' => 'kk',
                        '[kepala_kk]' => $warga->nama_warga,
                        '[ttl]' => $warga->tempat_lahir . '/' . Carbon::parse($warga->tanggal_lahir)->format('d F Y'),
                        '[agama]' => 'islam',
                        '[sex]' => $warga->jenis_kelamin,
                        '[alamat]' => $warga->desa->alamat,
                        '[status]' => 'status',
                        '[pendidikan]' => 'pendidikan',
                        '[pekerjaan]' => 'pekerjaan',
                        '[warga_negara]' => 'Indonesia',
                        '[form_berlaku_dari]' => $request->berlaku_mulai,
                        '[form_berlaku_sampai]' => $request->berlaku_sampai,
                        '[form_jenis_keramaian]' => $request->jenis_keramaian,
                        '[form_keperluan]' => $request->keperluan,
                        '[penandatangan]' => $warga->nama_warga,
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
                    // dd($array);
                    DB::commit();
                    break;
                case 4:
                    // dd($request->all());
                    $doc = '4_surat_ket_pengantar';
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
                    PermohonanSuratPengantar::create([
                        'permohonan_surat_id' => $id,
                        'berlaku_mulai' => $request->berlaku_mulai,
                        'berlaku_sampai' => $request->berlaku_sampai,
                        'keperluan' => $request->keperluan
                    ]);
                    $body = array(
                        '[nama]' => $warga->nama_warga,
                        '[no_ktp]' => $warga->nik,
                        '[no_kk]' => 'kk',
                        '[kepala_kk]' => $warga->nama_warga,
                        '[ttl]' => $warga->tempat_lahir . '/' . Carbon::parse($warga->tanggal_lahir)->format('d F Y'),
                        '[usia]' => Carbon::now()->format('Y') - Carbon::parse($warga->tanggal_lahir)->format('Y'),
                        '[agama]' => 'islam',
                        '[sex]' => $warga->jenis_kelamin,
                        '[alamat]' => $warga->desa->alamat,
                        '[status]' => 'status',
                        '[pendidikan]' => 'pendidikan',
                        '[pekerjaan]' => 'pekerjaan',
                        '[warga_negara]' => 'Indonesia',
                        '[gol_darah]' => 'tidak di ketahui',
                        '[mulai_berlaku]' => $request->berlaku_mulai,
                        '[tgl_akhir]' => $request->berlaku_sampai,
                        '[keperluan]' => $request->keperluan,
                        '[penandatangan]' => $warga->nama_warga,
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
                    break;
                case 5:
                    dd($request->all());
                    $doc = '5_surat_kuasa';
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
                    PermohonanSuratKuasa::create([
                        'permohonan_surat_id' => $id,
                        'nama_pem' => $request->nama_pem,
                        'tempat_lahir_pem' => $request->tempat_lahir_pem,
                        'tanggal_lahir_pem' => $request->tanggal_lahir_pem,
                        'jenis_kelamin_pem' => $request->jenis_kelamin_pem,
                        'alamat_pem' => $request->alamat_pem,
                        'desa_pem' => $request->desa_pem,
                        'kecamatan_pem' => $request->kecamatan_pem,
                        'kabupaten_pem' => $request->kabupaten_pem,
                        'nama_pen' => $request->nama_pen,
                        'nik_pen' => $request->nik_pen,
                        'tempat_lahir_pen' => $request->tempat_lahir_pen,
                        'tanggal_lahir_pen' => $request->tanggal_lahir_pen,
                        'umur_pen' => $request->umur_pen,
                        'jenis_kelamin_pen' => $request->jenis_kelamin_pen,
                        'pekerjaan_pen' => $request->pekerjaan_pen,
                    ]);
                    $body = array(
                        '[nama_pemberi_kuasa]' => '',
                        '[nik_pemberi_kuasa]' => '',
                        '[tempat_lahir_pemberi_kuasa]' => '',
                        '[tanggal_lahir_pemberi_kuasa]' => '',
                        '[umur_pemberi_kuasa]' => '',
                        '[jkpemberi_kuasa]' => '',
                        '[pekerjaanpemberi_kuasa]' => '',
                        '[alamat_pemberi_kuasa]' => '',
                        '[form_desapemberi_kuasa]' => '',
                        '[form_kecpemberi_kuasa]' => '',
                        '[form_kabpemberi_kuasa]' => '',
                        '[nama_penerima_kuasa]' => '',
                        '[nik_penerima_kuasa]' => '',
                        '[tempat_lahir_penerima_kuasa]' => '',
                        '[tanggal_lahir_penerima_kuasa]' => '',
                        '[umur_penerima_kuasa]' => '',
                        '[jkpenerima_kuasa]' => '',
                        '[pekerjaanpenerima_kuasa]' => '',
                        '[alamat_penerima_kuasa]' => '',
                        '[form_desapenerima_kuasa]' => '',
                        '[form_kecpenerima_kuasa]' => '',
                        '[form_kabpenerima_kuasa]' => '',
                        '[untuk_keperluan]' => '',

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
            // dd($array);
            $nama_file = $doc . '.doc';
            try {
                return \WordTemplate::export($file, $array, $nama_file);
            } catch (\Throwable $th) {
                dd($th->getMessage());
            }
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
