<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermohonanSuratKuasasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permohonan_surat_kuasas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permohonan_surat_id')->constrained('permohonan_surats');
            $table->string('nama_pem');
            $table->string('tempat_lahir_pem');
            $table->date('tanggal_lahir_pem');
            $table->enum('jenis_kelamin_pem',['Laki-Laki','Perempuan']);
            $table->text('alamat_pem');
            $table->string('desa_pem');
            $table->string('kecamatan_pem');
            $table->string('kabupaten_pem');
            
            $table->string('nama_pen');
            $table->string('nik_pen');
            $table->string('tempat_lahir_pen');
            $table->date('tanggal_lahir_pen');
            $table->string('umur_pen');
            $table->enum('jenis_kelamin_pen',['Laki-Laki','Perempuan']);
            $table->string('pekerjaan_pen');
            $table->string('alamat_pen');
            $table->string('desa_pen');
            $table->string('kecamatan_pen');
            $table->string('kabupaten_pen');
            
            $table->string('keperluan');
            
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
        Schema::dropIfExists('permohonan_surat_kuasas');
    }
}
