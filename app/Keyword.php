<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{
    protected $guarded = [];

    public function tweet()
    {
        return $this->belongsTo(Tweet::class);
    }
}
