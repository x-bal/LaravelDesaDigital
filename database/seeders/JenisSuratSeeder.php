<?php

namespace Database\Seeders;

use App\Models\JenisSurat;
use Illuminate\Database\Seeder;

class JenisSuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jenis = [
            'Surat Pengantar SKCK (Surat Keterangan Catatan Kepolisian)',
            'Surat Pengantar Laporan Kehilangan',
            'Surat Pengantar Izin Keramaian',
            'Surat Pengantar',
            'Surat Kuasa',
            'Surat Keterangan Usaha',
            'Surat Keterangan Tempat Berdomisili Usaha',
            'Surat Keterangan Pergi Kawin',
            'Surat Keterangan Penghasilan',
            'Surat Keterangan Pengalihan Jamkesda',
            'Surat Keterangan Kurang Mampu',
            'Surat Keterangan KTP Dalam Proses',
            'Surat Keterangan Kelahian',
            'Surat Keterangan Jual Beli',
            'Surat Keterangan Belum Menikah',
            'Surat Keterangan Beda Identitas',
            'Surat Bepergian/Jalan',
        ];
        foreach($jenis as $data){
            JenisSurat::create([
                'jenis_surat' => $data
            ]);
        }
    }
}
