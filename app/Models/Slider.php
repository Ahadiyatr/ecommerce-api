<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image',
        'url',
    ];

    public function getApiResponseAttribute()
    {
        return [
            'title' => $this->title,
            'image' => asset($this->image),
            'url' => $this->url,
        ];
    }
}
