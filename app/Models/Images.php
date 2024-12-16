<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Images extends Model
{
    protected $guarded = [];
    protected $table = 'images';
    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
