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
        Schema::create("scores", function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->unique()->constrained("users")->cascadeOnDelete();
            $table->integer("afektif_1")->default(100);
            $table->integer("sholat")->default(100);
            $table->integer("membaca_alquran")->default(25);
            $table->integer("ceramah_agama")->default(25);
            $table->integer("pre_test")->default(0);
            $table->integer("post_test")->default(0);
            $table->integer("psikomotorik_1")->default(30);
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
        Schema::dropIfExists("scores");
    }
};
