<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermohonanSuratKelahiransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permohonan_surat_kelahirans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permohonan_surat_id')->constrained('permohonan_surats');
            $table->foreignId('orangtua_ayah_id')->constrained('wargas');
            $table->foreignId('orangtua_ibu_id')->constrained('wargas');
            $table->string('anak_ke');
            $table->string('nama_anak');
            $table->enum('jenis_kelamin_anak', ['Laki-Laki', 'Perempuan']);
            $table->date('tanggal_lahir_anak');
            $table->time('pukul_lahir_anak');
            $table->string('tempat_lahir_anak');
            $table->string('hubungan_pelapor');
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
        Schema::dropIfExists('permohonan_surat_kelahirans');
    }
}
