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
        Schema::create("afektif_indicators", function (Blueprint $table) {
            $table->id();
            $table->foreignId("category_id")->constrained("afektif_indicator_categories");
            $table->string("indicator");
            $table->integer("poin_pengurangan")->default(5);
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
        Schema::dropIfExists("afektif_indicators");
    }
};
