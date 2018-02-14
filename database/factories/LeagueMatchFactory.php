<?php

use Faker\Generator as Faker;

$factory->define(App\LeagueMatch::class, function (Faker $faker) {
    $league = factory(App\League::class, 1)->create()->first();
    $venue = factory(App\Venue::class, 1)->create()->first();
    $home = factory(App\Team::class, 1)->create()->first();
    $away = factory(App\Team::class, 1)->create()->first();

    return [
        'league_id' => $league->id,
        'venue_id' => $venue->id,
        'match_date' => $faker->date(),
        'home_team_id' => $home,
        'away_team_id' => $away
    ];
});
