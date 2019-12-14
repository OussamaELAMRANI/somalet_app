<?php

use Illuminate\Database\Seeder;

class SizesTableSeeder extends Seeder
{
   /**
    * Run the database seeds.
    *
    * @return void
    */
   public function run()
   {
      for ($i = 26; $i < 45; $i++) {
         factory(App\ShoeSize::class)->create(["size" => $i]);
      }
   }
}
