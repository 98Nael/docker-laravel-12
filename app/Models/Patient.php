<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Patient extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'age',
        'gender',
        'image',
    ];
    protected $appends = [
        'image_url',

    ];

    public function getImageUrlAttribute()
    {
        if ($this->image)
            return Storage::url($this->image);
        else null;
    }

}
