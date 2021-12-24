<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDaftarKurangMampusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daftar_kurang_mampus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_kurang_mampu_id')->constrained('permohonan_surat_kurang_mampus');
            $table->foreignId('warga_id')->constrained('wargas');
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
        Schema::dropIfExists('daftar_kurang_mampus');
    }
}
