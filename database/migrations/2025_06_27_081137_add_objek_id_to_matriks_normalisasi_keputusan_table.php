<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('matriks_normalisasi_keputusan', function (Blueprint $table) {
            $table->unsignedBigInteger('objek_id')->after('kriteria_id');

            // Optional: jika objek_id berasal dari tabel 'objek'
            $table->foreign('objek_id')->references('id')->on('objek')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('matriks_normalisasi_keputusan', function (Blueprint $table) {
            $table->dropForeign(['objek_id']);
            $table->dropColumn('objek_id');
        });
    }
};
