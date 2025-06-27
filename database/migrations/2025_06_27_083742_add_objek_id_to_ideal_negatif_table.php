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
        Schema::table('ideal_negatif', function (Blueprint $table) {
            if (!Schema::hasColumn('ideal_negatif', 'objek_id')) {
                $table->unsignedBigInteger('objek_id')->nullable()->after('id');
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
        Schema::table('ideal_negatif', function (Blueprint $table) {
            if (Schema::hasColumn('ideal_negatif', 'objek_id')) {
                $table->dropColumn('objek_id');
            }
        });
    }
};
