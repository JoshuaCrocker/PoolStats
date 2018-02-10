<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeagueFramePlayer extends Model
{
    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
