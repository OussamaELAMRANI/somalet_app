<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('cmd_number');
            $table->date('date_cmd');
            $table->unsignedBigInteger('client_id');
            // [IN] => In Command, [OUT] => Delivered
            // [OUT] => By Magazinier And Can (Stock--)
            $table->enum('status', ['IN', 'OUT'])->default('IN');

            // Retour de Marchandise
            $table->integer('is_canceled')->default(0);
            $table->date('canceled_date')->nullable();
            $table->text('canceled_description')->nullable();
            $table->integer('validate_canceled')->default(0);;

            $table->foreign('client_id')
                ->references('id')
                ->on('clients')
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
        Schema::dropIfExists('orders');
    }
}
