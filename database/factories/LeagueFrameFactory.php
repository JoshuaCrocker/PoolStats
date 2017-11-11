<?php

use Faker\Generator as Faker;

$factory->define(App\LeagueFrame::class, function (Faker $faker) {
    $league_match = factory(App\LeagueMatch::class, 1)->create()->first();

    return [
        'league_match_id' => $league_match->id,
        'frame_number' => 1,
        'doubles' => FALSE
    ];
});
