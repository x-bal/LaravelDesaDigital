<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermohonanSuratBedaIdentitasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permohonan_surat_beda_identitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permohonan_surat_id')->constrained('permohonan_surats');
            $table->string('no_identitas')->nullable();
            $table->string('nama_identitas')->nullable();
            $table->string('tempat_lahir_identitas')->nullable();
            $table->date('tanggal_lahir_identitas')->nullable();
            $table->enum('jenis_kelamin_identitas', ['Laki-Laki', 'Perempuan'])->nullable();
            $table->text('alamat_identitas')->nullable();
            $table->enum('agama_identitas',['Islam', 'Kristen', 'Katolik', 'Hindu','Buddha','Konghucu', 'Dll'])->nullable();
            $table->string('pekerjaan_identitas')->nullable();
            $table->string('keterangan_identitas')->nullable();
            $table->string('perbedaan')->nullable();
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
        Schema::dropIfExists('permohonan_surat_beda_identitas');
    }
}
