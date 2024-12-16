<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $guarded = [];
    protected $table = 'events';

    public function images()
    {
        return $this->hasMany(EventImage::class);
    }
}
