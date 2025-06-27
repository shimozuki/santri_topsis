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
        Schema::table('hasil_solusi_topsis', function (Blueprint $table) {
            // Drop FK dulu jika ada
            if (Schema::hasColumn('hasil_solusi_topsis', 'alternatif_id')) {
                $table->dropForeign(['alternatif_id']);
                $table->dropColumn('alternatif_id');
            }

            // Tambah objek_id jika belum ada
            if (!Schema::hasColumn('hasil_solusi_topsis', 'objek_id')) {
                $table->unsignedBigInteger('objek_id')->after('id')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hasil_solusi_topsis', function (Blueprint $table) {
            $table->dropColumn('objek_id');

            $table->unsignedBigInteger('alternatif_id')->after('id')->nullable();
            $table->foreign('alternatif_id')->references('id')->on('alternatif')->onDelete('cascade');
        });
    }
};
