<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
   /**
    * Run the migrations.
    *
    * @return void
    */
   public function up()
   {
      Schema::create('users', function (Blueprint $table) {
         $table->bigIncrements('id');
         $table->string('username')->unique();
         $table->string('first_name');
         $table->string('last_name');
         $table->json('type_user')->nullable();
//            $table->timestamp('email_verified_at')->nullable();
         $table->string('password');
         $table->boolean('is_active')->default(true);
         $table->rememberToken();
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
      Schema::dropIfExists('users');
   }
}
