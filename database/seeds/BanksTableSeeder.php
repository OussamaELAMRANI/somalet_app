<?php

use Illuminate\Database\Seeder;
use App\Bank;

class BanksTableSeeder extends Seeder
{
   /**
    * Run the database seeds.
    *
    * @return void
    */
   public function run()
   {
      $Banks = [
         [
            'name' => 'IF',
            'company' => 'IF',
            'type' => 'IN'
         ],
         [
            'name' => 'MOLSAT',
            'company' => 'MOLSAT',
            'type' => 'IN'
         ],
         [
            'name' => 'BB',
            'company' => 'BB',
            'type' => 'IN'
         ],
         [
            'name' => 'CIH',
            'company' => 'CIH',
            'type' => 'OUT'
         ],
         [
            'name' => 'BP',
            'company' => 'BP',
            'type' => 'OUT'
         ], [
            'name' => 'CDM',
            'company' => 'CDM',
            'type' => 'OUT'
         ], [
            'name' => 'BMCE',
            'company' => 'BMCE',
            'type' => 'OUT'
         ], [
            'name' => 'B AGR',
            'company' => 'ARG',
            'type' => 'OUT'
         ],
         [
            'name' => 'BMCI',
            'company' => 'BMCI',
            'type' => 'OUT'
         ], [
            'name' => 'SG',
            'company' => 'SG',
            'type' => 'OUT'
         ],

      ];

      foreach ($Banks as $b) {
         factory(App\Bank::class)->create($b);
      }


   }
}
