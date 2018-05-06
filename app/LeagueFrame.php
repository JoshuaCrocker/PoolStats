<?php

namespace App;

use App\Support\Database\CacheQueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeagueFrame extends Model
{
    use SoftDeletes;

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($match) {
            $match->players->each->delete();
        });
    }

    public function endpoint()
    {
        return '/matches/' . $this->match->id . '/frames/' . $this->id;
    }

    public function players()
    {
        return $this->hasMany(LeagueFramePlayer::class, 'league_frame_id', 'id');
    }

    public function match()
    {
        return $this->belongsTo(LeagueMatch::class, 'league_match_id');
    }

    public function getDoublesAttribute($value)
    {
        return $value ? true : false;
    }

    public function getHomePlayerAttribute()
    {
        return $this->homePlayers->first();
    }

    public function getHomePlayersAttribute()
    {
        $homeTeamID = $this->match->homeTeam->id;

        $homePlayers = $this->getPlayersOnTeam($homeTeamID);

        return $homePlayers;
    }

    private function getPlayersOnTeam($teamID)
    {
        $players = $this->players->map(function ($player) {
            return $player->player;
        })->filter(function ($player) use ($teamID) {
            return optional($player->team)->id == $teamID;
        })->values();

        return collect([
            isset($players[0]) ? $players[0] : new Player(),
            isset($players[1]) ? $players[1] : new Player()
        ]);
    }

    public function getAwayPlayerAttribute()
    {
        return $this->awayPlayers->first();
    }

    public function getAwayPlayersAttribute()
    {
        $awayTeamID = $this->match->awayTeam->id;

        $awayPlayers = $this->getPlayersOnTeam($awayTeamID);

        return $awayPlayers;
    }

    public function getTypeAttribute()
    {
        return $this->doubles ? 'double' : 'single';
    }

    public function isWinner($player)
    {
        $player = $this->players->where('player_id', $player->id)->first();

        return $player->winner;
    }

    public function isDraw()
    {
        foreach ($this->players as $player) {
            if ($player->winner) return false;
        }

        return true;
    }
}
