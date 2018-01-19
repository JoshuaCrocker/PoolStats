<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class League extends Model
{
    /**
     * Get the URL endpoint for the Team Model
     *
     * @return string
     */
    public function endpoint()
    {
        return '/leagues/' . $this->id;
    }
}
