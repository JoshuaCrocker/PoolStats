<?php

use Faker\Generator as Faker;

$factory->define(App\TeamVenue::class, function (Faker $faker) {
    $team = factory(App\Team::class, 1)->create()->first();
    $venue = factory(App\Venue::class, 1)->create()->first();

    return [
        'team_id' => $team->id,
        'venue_id' => $venue->id,
        'venue_from' => $faker->dateTimeThisYear(),
        'venue_to' => null
    ];
});
