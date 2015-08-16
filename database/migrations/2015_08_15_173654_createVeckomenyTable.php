<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVeckomenyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("vecko_meny", function (Blueprint $table) {
            $table->increments("id");
            $table->integer("mat_id")->unsigned();
            $table->foreign("mat_id")->references("id")->on("mat");
            $table->date("datum");
            $table->integer("vecka");
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
        Schema::drop("vecko_meny");
    }
}
