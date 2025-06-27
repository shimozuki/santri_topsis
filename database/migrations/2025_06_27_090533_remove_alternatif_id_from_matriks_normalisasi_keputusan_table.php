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
            // Hapus foreign key dulu
            $table->dropForeign(['alternatif_id']);

            // Baru hapus kolomnya
            $table->dropColumn('alternatif_id');
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
            $table->unsignedBigInteger('alternatif_id')->nullable();

            // Tambahkan kembali foreign key jika perlu
            $table->foreign('alternatif_id')->references('id')->on('alternatif')->onDelete('cascade');
        });
    }
};
