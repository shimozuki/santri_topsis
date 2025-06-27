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
        Schema::create('kuota_seleksi', function (Blueprint $table) {
            $table->id();
            $table->string('jenjang'); // contoh: SMP, SMA
            $table->year('tahun');     // contoh: 2025
            $table->integer('jumlah_kuota');
            $table->timestamps();

            $table->unique(['jenjang', 'tahun']); // supaya tidak ada duplikat kuota per jenjang dan tahun
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kuota_seleksi');
    }
};
