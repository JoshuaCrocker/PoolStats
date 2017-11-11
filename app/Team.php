<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Team
 * @package App
 */
class Team extends Model
{
    /**
     * Get the URL endpoint for the Team Model
     *
     * @return string
     */
    public function endpoint()
    {
        return '/teams/' . $this->id;
    }
}
