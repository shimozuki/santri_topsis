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
        Schema::table('solusi_ideal_positif', function (Blueprint $table) {
            $table->dropForeign(['alternatif_id']);

            $table->dropColumn('alternatif_id');
            if (!Schema::hasColumn('solusi_ideal_positif', 'objek_id')) {
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
        Schema::table('solusi_ideal_positif', function (Blueprint $table) {
            $table->dropForeign(['alternatif_id']);
            $table->dropColumn('alternatif_id');
            $table->dropColumn('objek_id');
        });
    }
};
