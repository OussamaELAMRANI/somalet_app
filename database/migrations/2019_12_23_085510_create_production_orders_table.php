<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductionOrdersTable extends Migration
{
   /**
    * Run the migrations.
    *
    * @return void
    */
   public function up()
   {
      Schema::create('production_orders', function (Blueprint $table) {

         $table->bigIncrements('id');
         $table->unsignedBigInteger('ps_id');

         $table->integer('order_quantity');
         $table->integer('fabric_quantity')->default(0);

         $table->foreign('ps_id')
            ->references('id')
            ->on('product_size');

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
      Schema::dropIfExists('production_orders');
   }
}
