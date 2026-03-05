<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gravatar extends Model
{
    protected $fillable = [
        'email',
        'avatar'
    ];
}