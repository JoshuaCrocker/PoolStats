<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PlayerTeam
 * @package App
 */
class PlayerTeam extends Model
{
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
}
