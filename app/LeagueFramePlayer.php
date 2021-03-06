<?php

namespace App;

use App\Support\Database\CacheQueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeagueFramePlayer extends Model
{
    use SoftDeletes;

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function getFrameAttribute()
    {
        return LeagueFrame::where('id', $this->league_frame_id)->get()->first();
    }
}
