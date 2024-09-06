<?php

use App\Helper\Routes\RouteHelper;

use Illuminate\Support\Facades\Route;

// all url is prefixed with api in api.php file

Route::prefix("v1")
    ->middleware([
        'auth:sanctum'
    ])
    ->group(function () { // prefixing all route by v1

        // require the helper function to include Routes
        RouteHelper::includeRouteFiles(__DIR__ . '/api/v1');
});


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


