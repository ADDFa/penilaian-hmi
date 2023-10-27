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
        Schema::create("user_scores", function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users");
            $table->integer("tingkah_laku")->default(100);
            $table->integer("tata_bahasa")->default(100);
            $table->integer("pakaian")->default(100);
            $table->integer("ketepatan_waktu")->default(100);
            $table->integer("sholat")->default(100);
            $table->integer("membaca_alquran")->default(25);
            $table->integer("ceramah_agama")->default(25);
            $table->integer("pre_test");
            $table->integer("post_test");
            $table->integer("penguasaan_kelompok")->default(0);
            $table->integer("problem_solving")->default(0);
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
        Schema::dropIfExists("user_scores");
    }
};
