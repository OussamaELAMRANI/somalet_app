<?php

use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        factory(App\Category::class, 3)->create()->each(function ($c) {
            $c->sub_categories()->saveMany(factory(\App\SubCategory::class, 2)->make());
        });

    }
}
