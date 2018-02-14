<?php

namespace App;

use App\Support\Database\CacheQueryBuilder;
use Illuminate\Database\Eloquent\Model;

class LeagueFramePlayer extends Model
{
    use CacheQueryBuilder;

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
