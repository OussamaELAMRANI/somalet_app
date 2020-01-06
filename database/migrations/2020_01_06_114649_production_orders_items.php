<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductionOrdersItems extends Migration
{
   /**
    * Run the migrations.
    *
    * @return void
    */
   public function up()
   {
      Schema::create('po_items', function (Blueprint $table) {
         $table->unsignedBigInteger('cmd_id');
         $table->unsignedBigInteger('ps_id');

         $table->integer('order_quantity');
         $table->integer('fabric_quantity')->default(0);
         $table->float('price')->nullable();;
         $table->float('sell_price')->nullable();

         $table->foreign('ps_id')
            ->references('id')
            ->on('product_size')
            ->onUpdate('cascade');

         $table->foreign('cmd_id')
            ->references('id')
            ->on('production_orders')
            ->onUpdate('cascade');

//         $table->softDeletes();
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
      Schema::create('po_items', function (Blueprint $table) {
         Schema::dropIfExists('po_items');
      });
   }
}
