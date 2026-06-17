<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gravatar extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'avatar',
        'size',
        'rating',
        'default_image',
        'is_favorite',
        'has_real_gravatar',
        'gravatar_checked_at'
    ];

    protected $casts = [
        'is_favorite' => 'boolean',
        'has_real_gravatar' => 'boolean',
        'gravatar_checked_at' => 'datetime'
    ];

    protected $attributes = [
        'size' => 200,
        'rating' => 'g',
        'default_image' => 'identicon',
        'is_favorite' => false
    ];
}