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
        Schema::create("afective_indicator_categories", function (Blueprint $table) {
            $table->id();
            $table->enum("category", ["Tingkah Laku", "Tata Bahasa", "Pakaian", "Ketepatan Waktu"]);
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
        Schema::dropIfExists("afective_indicator_categories");
    }
};
