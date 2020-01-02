<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OrderPS extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_ps', function (Blueprint $table) {
            $table->unsignedBigInteger('cmd_id');
            $table->unsignedBigInteger('ps_id');


            $table->foreign('ps_id')
                ->references('id')
                ->on('product_size');

            $table->foreign('cmd_id')
                ->references('id')->on('orders')
                ->onUpdate('cascade');


            $table->integer('qte')->default(0);
            $table->double('price');
            $table->double('discount');

            $table->integer('is_replaced')->default(0);
            $table->integer('is_replaceable')->default(0);

           $table->softDeletes();
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
        Schema::dropIfExists('order_ps');

    }
}
