<?php

use Illuminate\Database\Seeder;

class PaymentTypeTableSeeder extends Seeder
{
   /**
    * Run the database seeds.
    *
    * @return void
    */
   public function run()
   {
      $types = [
         'ESPECE',
         'EFFET',
         'CHEQUE',
         'BON',
         'REMISE',
         'AVOIR',
      ];

      foreach ($types as $t) {
         factory(\App\PaymentType::class)->create(['type' => $t]);
      }
   }
}
