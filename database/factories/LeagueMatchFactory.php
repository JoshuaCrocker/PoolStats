<?php

use Faker\Generator as Faker;

$factory->define(App\LeagueMatch::class, function (Faker $faker) {
    $league = factory(App\League::class, 1)->create()->first();
    $venue = factory(App\Venue::class, 1)->create()->first();

    return [
        'league_id' => $league->id,
        'venue_id' => $venue->id
    ];
});
