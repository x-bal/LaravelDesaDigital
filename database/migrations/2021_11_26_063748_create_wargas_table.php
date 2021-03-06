<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWargasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wargas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->constrained('desas');
            $table->foreignId('kecamatan_id')->constrained('kecamatans');
            $table->foreignId('kabupaten_id')->constrained('kabupatens');
            $table->foreignId('user_id')->nullable();
            $table->string('kk')->nullable();
            $table->integer('nik')->unique();
            $table->string('nama_warga');
            $table->enum('jenis_kelamin', ['Laki-Laki', 'Perempuan']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('agama',['Islam', 'Kristen', 'Katolik', 'Hindu','Buddha','Konghucu', 'Dll'])->nullable();
            $table->string('pekerjaan')->nullable();
            $table->string('pendidikan')->nullable();
            $table->string('alamat')->nullable();
            $table->string('warga_negara')->nullable();
            $table->enum('status_pernikahan',['Belum Menikah', 'Menikah','-'])->nullable();
            $table->enum('golongan_darah',['A','B','O','AB','-'])->nullable();
            $table->string('status')->nullable()->default('ada');
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
        Schema::dropIfExists('wargas');
    }
}
