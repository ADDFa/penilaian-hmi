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
        Schema::create("fouls", function (Blueprint $table) {
            $table->id();
            $table->string("foul");
            $table->enum("category", ["Tingkah Laku", "Tata Bahasa", "Pakaian", "Ketepatan Waktu", "Meninggalkan Sholat"]);
            $table->integer("point");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("fouls");
    }
};
