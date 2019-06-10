<?php

use App\Unit;
use Illuminate\Database\Seeder;

class UnityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $unities = [
            "Pièce",
            "Paire",
            "Mètre",
            "Kg (rouleau)",
            "Kg",
            "FUTS",
            "Box",
        ];

        foreach ($unities as $value) {
            factory(App\Unit::class)->create(["name" => $value]);
        }


    }
}
