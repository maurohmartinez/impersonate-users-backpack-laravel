<?php

use Illuminate\Support\Facades\Route;
use MHMartinez\ImpersonateUser\app\Http\Controllers\ImpersonateUser;

Route::group([
    'middleware' => config('backpack.base.web_middleware', 'web'),
    'prefix' => config('backpack.base.route_prefix'),
],
    function () {
        Route::post('impersonate-user-start', [ImpersonateUser::class, 'impersonateUser'])->name('impersonate_user.start');
        Route::post('impersonate-user-stop', [ImpersonateUser::class, 'stopImpersonateUser'])->name('impersonate_user.stop');
    });
