<?php

use Illuminate\Support\Facades\Route;
use Petersuhm\Ogkit\Http\Controllers\OgImageController;

Route::prefix('ogkit')->group(function () {
    Route::get('/og.jpeg', OgImageController::class);
});
