<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Activity extends Model
{
    protected $table = "activities";

    protected $fillable = [
        'name',
    ];

    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'user_activity');
    }

}
