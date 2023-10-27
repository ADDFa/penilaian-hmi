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
        Schema::create("afective_indicators", function (Blueprint $table) {
            $table->id();
            $table->enum("category", ["Tingkah Laku", "Tata Bahasa", "Pakaian dan Atribut"]);
            $table->string("foul");
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
        Schema::dropIfExists("afective_indicators");
    }
};
