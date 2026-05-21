<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GravatarController;

Route::get('/', [GravatarController::class, 'index']);

Route::post('/generate', [GravatarController::class, 'generate'])->name('generate.avatar');

Route::delete('/delete/{id}', [GravatarController::class, 'delete'])->name('delete.avatar');

// NEW Routes
Route::post('/bulk-generate', [GravatarController::class, 'bulkGenerate'])->name('bulk.generate');
Route::delete('/clear-all', [GravatarController::class, 'clearAll'])->name('clear.all');
Route::get('/export-csv', [GravatarController::class, 'exportCsv'])->name('export.csv');
Route::post('/preview', [GravatarController::class, 'preview'])->name('preview.avatar');
Route::get('/stats', [GravatarController::class, 'stats'])->name('avatar.stats');