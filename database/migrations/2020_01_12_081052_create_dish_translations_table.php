<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDishTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dish_translations', function (Blueprint $table) {
            $table->primary(['dish_id','locale']);
            $table->unsignedBigInteger('dish_id');
            $table->string('locale')->index;
            $table->string('title');
            $table->string('description');
            $table->unique(['dish_id','locale']);
            $table->foreign('dish_id')->references('id')->on('dishes')->onDelete('cascade');
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
        Schema::dropIfExists('dish_translations');
    }
}
