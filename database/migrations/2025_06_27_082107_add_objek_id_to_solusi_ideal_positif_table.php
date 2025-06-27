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
            $table->unsignedBigInteger('objek_id')->after('id');
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
            $table->dropColumn('objek_id');
        });
    }
};
