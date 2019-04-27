<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        todo add Type:[Intern,Externe]
        Schema::create('providers', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('steName')->nullable();
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
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('providers');
    }
}
