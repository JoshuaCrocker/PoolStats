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
            return $current;
        }

        if ($this->terminates_today) {
            return 'Ending Today';
        }

        return $value;
    }

    public function getTerminatesTodayAttribute()
    {
        $member_to = Carbon::parse($this->getOriginal('member_to'));

        return $member_to->format('y-m-d') == Carbon::now()->format('y-m-d');
    }
}
