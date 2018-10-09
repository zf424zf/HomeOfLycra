<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductHotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_hots', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pid');
            $table->foreign('pid')->references('id')->on('products')->onDelete('cascade');
            $table->integer('order')->default(0);
            $table->integer('status')->default(0);
            $table->integer('is_recommend')->default(0);
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
        Schema::dropIfExists('product_hots');
    }
}
