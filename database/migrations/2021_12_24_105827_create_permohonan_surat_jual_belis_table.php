<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermohonanSuratJualBelisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permohonan_surat_jual_belis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permohonan_surat_id')->constrained('permohonan_surats');
            $table->foreignId('pihak_id')->constrained('wargas');
            $table->string('nama_barang');
            $table->string('jenis_barang');
            $table->string('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permohonan_surat_jual_belis');
    }
}
