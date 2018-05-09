<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

/**
 * Class PlayerTeam
 * @package App
 */
class PlayerTeam extends Model
{
    use SoftDeletes;

    /**
     * Get the URL endpoint for the Player Model
     *
     * @return string
     */
    public function endpoint()
    {
        return '/teams/' . $this->team->id . '/membership/' . $this->id;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function getMemberToAttribute($value)
    {
        if ($value == null) {
            return 'Current';
        }

        return $value;
    }

    public function getTerminatesTodayAttribute()
    {
        $member_to = $this->getOriginal('member_to');

        if ($member_to == null) {
            return false;
        }

        $member_to_date = Carbon::parse($member_to);

        return $member_to_date->format('y-m-d') == Carbon::now()->format('y-m-d');
    }
}
