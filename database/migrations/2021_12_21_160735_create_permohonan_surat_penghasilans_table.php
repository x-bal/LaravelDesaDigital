<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermohonanSuratPenghasilansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permohonan_surat_penghasilans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permohonan_surat_id')->constrained('permohonan_surats');
            $table->foreignId('orangtua_ayah_id')->constrained('wargas');
            $table->foreignId('orangtua_ibu_id')->constrained('wargas');
            $table->string('no_induk');
            $table->string('sekolah');
            $table->string('kelas');
            $table->string('jurusan');
            $table->integer('penghasilan_ayah');
            $table->integer('penghasilan_ibu');
            $table->date('berlaku_mulai')->nullable();
            $table->date('berlaku_sampai')->nullable();
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
        Schema::dropIfExists('permohonan_surat_penghasilans');
    }
}
