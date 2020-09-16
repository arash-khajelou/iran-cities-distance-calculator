<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLatLonToLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->string("lat")->nullable();
            $table->string("lon")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn("lat");
            $table->dropColumn("lon");
        });
    }
}
