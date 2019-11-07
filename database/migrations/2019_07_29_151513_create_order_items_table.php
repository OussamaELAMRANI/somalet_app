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

         $table->double('qte');
         $table->double('qte_rapport');
         $table->double('discount'); // Take Trace to Old Discount
         // $table->double('qte_price');
         // To Make Trace in DB, Because the [price, Discount] are Variables
         $table->double('price');

         $table->integer('is_replaced')->default(0);
         $table->integer('is_replaceable')->default(0);

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
