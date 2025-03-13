<?php

use Illuminate\Support\Facades\Route;

Route::controller(42sol\LaravelOnlyoffice\Http\Controllers\OnlyOfficeController::class)
    ->prefix('onlyoffice')
    ->group(function () {
        Route::get('/open', 'open')->name('onlyoffice.open');
        Route::post('/listen', 'listen')->name('onlyoffice.listen');
    });
