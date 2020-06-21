<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductSizeOnDelete extends Migration
{
   /**
    * Run the migrations.
    *
    * @return void
    */
   public function up()
   {
      Schema::table('product_size', function (Blueprint $table) {
         $table->dropForeign(['product_id']);
         $table->dropForeign(['size_id']);


         $table->foreign('product_id')
            ->references('id')
            ->on('products')
            ->onUpdate('cascade')
            ->onDelete('cascade');

         $table->foreign('size_id')
            ->references('id')
            ->on('shoe_sizes')
            ->onUpdate('cascade')
            ->onDelete('cascade');
      });
   }

   /**
    * Reverse the migrations.
    *
    * @return void
    */
   public function down()
   {
      Schema::table('product_size', function (Blueprint $table) {
         //
      });
   }
}
