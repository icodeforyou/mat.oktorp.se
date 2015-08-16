<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VeckoMeny extends Model
{
    protected $table = "vecko_meny";

    public function matratt()
    {
        return $this->belongsTo('App\Mat', 'mat_id');
    }
}
