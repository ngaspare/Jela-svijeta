<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDishIngredientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dish_ingredient', function (Blueprint $table) {
            $table->primary(['dish_id','ingredient_id']);
            $table->unsignedBigInteger('dish_id');
            $table->unsignedBigInteger('ingredient_id');
            $table->unique(['dish_id', 'ingredient_id']);
            $table->foreign('dish_id')->references('id')->on('dishes')->onDelete('cascade');
            $table->foreign('ingredient_id')->references('id')->on('ingredients')->onDelete('cascade');
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
        Schema::dropIfExists('dish_ingredient');
    }
}
