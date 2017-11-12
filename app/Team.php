<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Team
 * @package App
 */
class Team extends Model
{
    use SoftDeletes;

    /**
     * Don't auto-apply mass assignment protection.
     *
     * @var array
     */
    protected $guarded = [];

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
