<?php

use Contensio\Plugins\ContentPassword\Http\Controllers\Admin\ContentPasswordAdminController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'contensio.admin'])
    ->prefix('account/settings')
    ->group(function () {
        Route::get('content-password', [ContentPasswordAdminController::class, 'index'])
            ->name('contensio-content-password.index');

        Route::post('content-password/{contentId}/set', [ContentPasswordAdminController::class, 'set'])
            ->name('contensio-content-password.set');

        Route::post('content-password/{contentId}/remove', [ContentPasswordAdminController::class, 'remove'])
            ->name('contensio-content-password.remove');
    });
