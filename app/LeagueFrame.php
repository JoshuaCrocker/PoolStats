<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeagueFrame extends Model
{
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

    private function getPlayersOnTeam($teamID)
    {
        return $this->players->map(function ($player) {
            return $player->player;
        })->filter(function ($player) use ($teamID) {
            return $player->team->id == $teamID;
        });
    }

    public function getHomePlayerAttribute()
    {
        $homeTeamID = $this->match->homeTeam->id;

        $homePlayers = $this->getPlayersOnTeam($homeTeamID);

        return $homePlayers->first();
    }

    public function getAwayPlayerAttribute()
    {
        $awayTeamID = $this->match->awayTeam->id;

        $awayPlayers = $this->getPlayersOnTeam($awayTeamID);

        return $awayPlayers->first();
    }

    public function isWinner($player)
    {
        $player = $this->players->where('player_id', $player->id)->first();

        return $player->winner;
    }
}
