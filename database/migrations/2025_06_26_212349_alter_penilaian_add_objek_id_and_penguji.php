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
            $table->foreignId('objek_id')->nullable()->after('id')->constrained('objek')->onDelete('cascade');

            if (Schema::hasColumn('penilaian', 'alternatif_id')) {
                $table->dropForeign(['alternatif_id']);
                $table->dropColumn('alternatif_id');
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
        Schema::table('penilaian', function (Blueprint $table) {
            //
        });
    }
};
