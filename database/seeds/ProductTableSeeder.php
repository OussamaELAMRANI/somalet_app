<?php

use App\Color;
use App\Unit;
use Illuminate\Database\Seeder;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        $color = factory(App\Color::class)->create();
//        $unit = factory(App\Unit::class)->create();
        factory(App\Provider::class, 10)->create()->each(function ($c) {
            $color = factory(App\Color::class)->create();
            $unit = factory(App\Unit::class)->create();
            $c->products()->saveMany(factory(App\Product::class, 10)->make(['color_id'=>$color->id,'unit_id'=>$unit->id]));
        });
    }
}
