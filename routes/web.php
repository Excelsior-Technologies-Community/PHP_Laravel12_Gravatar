<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GravatarController;

Route::get('/', [GravatarController::class,'index']);

Route::post('/generate',[GravatarController::class,'generate'])->name('generate.avatar');