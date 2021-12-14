<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAlamatToDesasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('desas', function (Blueprint $table) {
            $table->text('alamat')->after('nama_desa');
            $table->text('logo')->after('alamat');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('desas', function (Blueprint $table) {
            $table->dropIfExists('alamat');
            $table->dropIfExists('logo');
        });
    }
}
