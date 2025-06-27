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
        Schema::table('matriks_normalisasi_bobot_keputusan', function (Blueprint $table) {
            // Drop foreign key dan kolom alternatif_id
            $table->dropForeign(['alternatif_id']); // pastikan nama FK-nya benar
            $table->dropColumn('alternatif_id');

            // Tambahkan kolom objek_id dan relasinya
            $table->unsignedBigInteger('objek_id')->after('id');
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
        Schema::table('matriks_normalisasi_bobot_keputusan', function (Blueprint $table) {
            $table->dropForeign(['objek_id']);
            $table->dropColumn('objek_id');

            $table->unsignedBigInteger('alternatif_id')->after('id');
            $table->foreign('alternatif_id')->references('id')->on('alternatif')->onDelete('cascade');
        });
    }
};
