<?php

use Illuminate\Support\Facades\Route;

Route::controller(\sol42\LaravelOnlyoffice\Http\Controllers\OnlyOfficeController::class)
    ->prefix('onlyoffice')
    ->group(function () {
        Route::get('/open', 'open')->name('onlyoffice.open');
        Route::post('/listen', 'listen')->name('onlyoffice.listen');
    });
