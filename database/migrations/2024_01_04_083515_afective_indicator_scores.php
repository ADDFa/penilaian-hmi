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
        Schema::create("afective_indicator_scores", function (Blueprint $table) {
            $table->id();
            $table->foreignId("score_id")->constrained("scores")->cascadeOnDelete();
            $table->integer("tingkah_laku")->default(0);
            $table->integer("tata_bahasa")->default(0);
            $table->integer("pakaian")->default(0);
            $table->integer("ketepatan_waktu")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("afective_indicator_scores");
    }
};
