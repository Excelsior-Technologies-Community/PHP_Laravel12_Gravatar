<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GravatarController;

Route::get('/', [GravatarController::class, 'index']);

Route::post('/generate', [GravatarController::class, 'generate'])->name('generate.avatar');

Route::delete('/delete/{id}', [GravatarController::class, 'delete'])->name('delete.avatar');

Route::post('/bulk-generate', [GravatarController::class, 'bulkGenerate'])->name('bulk.generate');
Route::delete('/clear-all', [GravatarController::class, 'clearAll'])->name('clear.all');
Route::get('/export-csv', [GravatarController::class, 'exportCsv'])->name('export.csv');
Route::post('/preview', [GravatarController::class, 'preview'])->name('preview.avatar');
Route::get('/stats', [GravatarController::class, 'stats'])->name('avatar.stats');

Route::patch('/avatar/{id}', [GravatarController::class, 'update'])->name('update.avatar');
Route::patch('/avatar/{id}/favorite', [GravatarController::class, 'toggleFavorite'])->name('favorite.avatar');
Route::post('/avatar/{id}/refresh-cache', [GravatarController::class, 'refreshCache'])->name('refresh.cache');