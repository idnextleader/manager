<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventImage extends Model
{
    protected $guarded = [];
    protected $table = 'event_images';

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
