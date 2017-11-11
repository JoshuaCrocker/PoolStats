<?php

use Faker\Generator as Faker;

$factory->define(App\LeagueFramePlayer::class, function (Faker $faker) {
    $league_frame = factory(App\LeagueFrame::class, 1)->create()->first();
    $player = factory(App\Player::class, 1)->create()->first();

    return [
        'league_frame_id' => $league_frame->id,
        'player_id' => $player->id,
        'winner' => (rand(0, 1) == 0)
    ];
});
