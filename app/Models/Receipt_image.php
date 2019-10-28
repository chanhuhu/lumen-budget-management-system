<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receipt_image extends Model
{
    protected $fillable = [
        'receipt_id', 'filename'
    ];

    public function receipt()
    {
        return $this->belongsTo('App\Models\Receipt');
    }
}
