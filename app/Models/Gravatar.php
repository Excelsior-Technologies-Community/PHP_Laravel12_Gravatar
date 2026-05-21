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
        'size'
    ];

    protected $attributes = [
        'size' => 200
    ];
}