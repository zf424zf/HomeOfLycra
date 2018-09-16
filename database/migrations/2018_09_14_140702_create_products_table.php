<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            //
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->index();
            $table->string('icon')->nullable();
            $table->string('limit')->nullable();
            $table->string('price')->nullable();
            $table->text('desc')->nullable();
            $table->integer('apply_num')->nullable();
            $table->string('url')->nullable();
            $table->string('limit_date')->nullable();
            $table->string('limit_age')->nullable();
            $table->string('apply_time')->nullable();
            $table->string('check_type')->nullable();
            $table->integer('order')->default(0);
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
        Schema::dropIfExists('products');
    }
}
