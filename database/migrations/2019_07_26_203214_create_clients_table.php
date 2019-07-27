<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('nom')->nullable();
            $table->string('numTva')->nullable();
            $table->string('nSiret')->nullable();

            $table->string('firstName')->nullable();
            $table->string('lastName')->nullable();
            $table->text('address')->nullable();
            $table->integer('cp')->nullable();
            $table->text('city')->nullable();
            $table->text('country')->nullable();

            $table->text('phone')->nullable();
            $table->text('cell')->nullable();
            $table->text('email')->nullable();
            $table->text('skype')->nullable();
            $table->text('icp')->nullable();

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
        Schema::dropIfExists('clients');
    }
}
