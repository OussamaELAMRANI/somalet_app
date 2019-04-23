<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Provider;
use Faker\Generator as Faker;

$factory->define(Provider::class, function (Faker $faker) {
    return [
        'steName' => $faker->company,
        'numTva' => $faker->buildingNumber,
        'nSiret' => $faker->randomNumber(),
        'firstName' => $faker->firstName,
        'lastName' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'address' => $faker->address,
        'cp' => $faker->numberBetween(4,8),
        'city' => $faker->city,
        'country' => $faker->country,
        'phone' => $faker->phoneNumber,
        'cell' => $faker->phoneNumber,
        'email' => $faker->email,
        'skype' => $faker->email,
        'icp' => Str::random(10),
    ];
});
