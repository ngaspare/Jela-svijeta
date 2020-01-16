<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDishTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dish_tag', function (Blueprint $table) {
            $table->primary(['dish_id','tag_id']);
            $table->unsignedBigInteger('dish_id');
            $table->unsignedBigInteger('tag_id');
            $table->unique(['dish_id', 'tag_id']);
            $table->foreign('dish_id')->references('id')->on('dishes')->onDelete('cascade');
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
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
        Schema::dropIfExists('dish_tag');
    }
}
