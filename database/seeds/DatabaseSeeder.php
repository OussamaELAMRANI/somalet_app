<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
   /**
    * Seed the application's database.
    *
    * @return void
    */
   public function run()
   {
        $this->call(CategoryTableSeeder::class);
//        $this->call(UnityTableSeeder::class);
//        $this->call(BanksTableSeeder::class);
//      $this->call(PaymentTypeTableSeeder::class);
//        $this->call(ProviderTableSeeder::class);
   }
}
