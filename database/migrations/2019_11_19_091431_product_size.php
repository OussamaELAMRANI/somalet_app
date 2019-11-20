<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductSize extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_size', function (Blueprint $table) {

            $table->increments('id');

            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('size_id');
            $table->double('weight')->default(0);

//            $table->primary(['product_id', 'size_id'], 'id_ps');

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onUpdate('cascade');

            $table->foreign('size_id')
                ->references('id')
                ->on('shoe_sizes')
                ->onUpdate('cascade');


            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_size');

    }
}
