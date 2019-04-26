<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Product::class, function (Faker $faker) {
    return [
        'reference' => $faker->unique()->userName,
        'name' => $faker->unique()->userAgent,
        'description' => $faker->text,
        'remark' => $faker->text,
        'img' => $faker->imageUrl(),
    ];
});
$factory->define(App\Color::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->colorName,
    ];
});
$factory->define(App\Unit::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->name,
    ];
});
