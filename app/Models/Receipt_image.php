<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receipt_image extends Model
{
    protected $table = 'images';

    protected $fillable = [
        'receipt_id', 'file_name'
    ];

    public function receipt()
    {
        return $this->belongsTo('App\Models\Receipt');
    }
}
