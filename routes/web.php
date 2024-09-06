<?php

use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


// should implement a auth password reset view to enable user to reset his password
Route::get('/reset-password/{token}', function ($token){
    return view('auth.password-reset', [
        'token' => $token
    ]);
})->middleware(['guest:'.config('fortify.guard')])
  ->name('password.reset');


Route::get('/app', function () { // for testing cookie
    return view('app');
});


if(App::environment('local')) { // for develop testing

    Route::get('/playground', function () {
        $user = User::factory()->make();
        Mail::to($user)->send(new WelcomeMail($user));
        return null;
    });
}
