<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderItemsTable extends Migration
{
   /**
    * Run the migrations.
    *
    * @return void
    */
   public function up()
   {
      Schema::create('order_items', function (Blueprint $table) {

         $table->unsignedBigInteger('cmd_id');
         $table->unsignedBigInteger('product_id');

         $table->integer('qte');
         $table->double('discount');
         $table->double('qte_price');

         $table->foreign('cmd_id')
            ->references('id')->on('orders')
            ->onUpdate('cascade');

         $table->foreign('product_id')
            ->references('id')->on('products')
            ->onUpdate('cascade');

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
      Schema::dropIfExists('order_items');
   }
}
