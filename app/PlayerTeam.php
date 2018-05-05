<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        return $value == null ? 'Current' : $value;
    }
}
