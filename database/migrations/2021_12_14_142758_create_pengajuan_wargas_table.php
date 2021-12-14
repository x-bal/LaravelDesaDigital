<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengajuanWargasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengajuan_wargas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permohonan_surat_id')->constrained('permohonan_surats');
            $table->string('nama');
            $table->string('nik');
            $table->string('tempat_lahir');
            $table->enum('jenis_kelamin',['Laki Laki','Perempuan']);
            $table->text('alamat');
            $table->string('agama');
            $table->string('status_perkawinan');
            $table->string('pekerjaan');
            $table->string('kewarganegaraan');
            $table->string('golongan_darah');
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
        Schema::dropIfExists('pengajuan_wargas');
    }
}
