<?php

use Faker\Generator as Faker;

$factory->define(App\PlayerTeam::class, function (Faker $faker) {
    $team = factory(App\Team::class, 1)->create()->first();
    $player = factory(App\Player::class, 1)->create()->first();

    return [
        'team_id' => $team->id,
        'player_id' => $player->id,
        'member_from' => $faker->dateTimeThisYear(),
        'member_to' => null
    ];
});
