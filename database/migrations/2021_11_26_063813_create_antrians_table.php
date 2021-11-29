<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAntriansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('antrians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warga_id')->constrained('wargas');
            $table->foreignId('desa_id')->constrained('desas');
            $table->foreignId('jenis_surat_id')->constrained('jenis_surats');
            $table->integer('no_antrian');
            $table->date('tanggal_antri')->default(Carbon::now()->format('Y-m-d'));
            $table->integer('status')->default(0);
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
        Schema::dropIfExists('antrians');
    }
}
