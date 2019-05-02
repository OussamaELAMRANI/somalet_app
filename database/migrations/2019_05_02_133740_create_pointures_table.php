<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePointuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pointures', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('pointure');
            $table->integer('poids');
            $table->unsignedBigInteger('product_id');

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
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
        Schema::dropIfExists('pointures');
    }
}
