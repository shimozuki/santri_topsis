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
        Schema::table('penilaian', function (Blueprint $table) {
            // Hapus constraint foreign key lama dulu
            $table->dropForeign(['sub_kriteria_id']);

            // Tambahkan kembali dengan cascade
            $table->foreign('sub_kriteria_id')
                ->references('id')
                ->on('sub_kriteria')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('penilaian', function (Blueprint $table) {
            $table->dropForeign(['sub_kriteria_id']);

            // Restore ke default (tanpa cascade)
            $table->foreign('sub_kriteria_id')
                ->references('id')
                ->on('sub_kriteria');
        });
    }
};
