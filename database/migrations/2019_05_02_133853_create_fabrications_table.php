<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFabricationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fabrications', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('matiere_id');
            $table->integer('taux');

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onUpdate('cascade');
            $table->foreign('matiere_id')
                ->references('id')
                ->on('products')
                ->onUpdate('cascade');
            $table->primary(['product_id','matiere_id']);
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
        Schema::dropIfExists('fabrications');
    }
}
