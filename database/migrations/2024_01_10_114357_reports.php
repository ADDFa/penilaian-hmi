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
        Schema::create("reports", function (Blueprint $table) {
            $table->id();
            $table->foreignId("score_id")->constrained("scores")->cascadeOnDelete();
            $table->foreignId("user_id")->unique()->constrained("users")->cascadeOnDelete();
            $table->enum("status", ["Lulus", "Lulus Bersyarat", "Tidak Lulus"])->nullable()->default(null);
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
        Schema::dropIfExists("reports");
    }
};
