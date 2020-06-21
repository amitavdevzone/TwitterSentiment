<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    protected $guarded = [];

    public function keywords()
    {
        return $this->hasMany(Keyword::class)
            ->orderBy('keyword');
    }
}
