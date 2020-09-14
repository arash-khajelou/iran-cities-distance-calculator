<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("locations", function (Blueprint $table) {
            $table->unsignedBigInteger("id");
            $table->primary("id");
            $table->string("name");
            $table->integer("type");
            $table->unsignedBigInteger("parent_id")->nullable();
            $table->foreign("parent_id")->references("id")->on("locations")
                ->onDelete("cascade");
            $table->unsignedBigInteger("ostan_id")->nullable();
            $table->foreign("ostan_id")->references("id")->on("locations")
                ->onDelete("cascade");
            $table->unsignedBigInteger("shahr_id")->nullable();
            $table->foreign("shahr_id")->references("id")->on("locations")
                ->onDelete("cascade");
            $table->unsignedBigInteger("bakhsh_id")->nullable();
            $table->foreign("bakhsh_id")->references("id")->on("locations")
                ->onDelete("cascade");
            $table->unsignedBigInteger("dehestan_id")->nullable();
            $table->foreign("dehestan_id")->references("id")->on("locations")
                ->onDelete("cascade");
            $table->json("geo_data")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("locations");
    }
}
