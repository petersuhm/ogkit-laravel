<?php

use Illuminate\Support\Facades\Route;
use Petersuhm\Ogkit\Http\Controllers\OgImageController;

Route::prefix('ogkit')->group(function () {
    Route::get('image', OgImageController::class)->name('ogkit.image');
});
