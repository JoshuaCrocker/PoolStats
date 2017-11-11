<?php

use Faker\Generator as Faker;

$factory->define(App\League::class, function (Faker $faker) {
    return [
        'name' => $faker->word()
    ];
});
