<?php
/** @var Router $router */

use App\Http\Controllers\HandleSocialiteCallback;
use App\Http\Controllers\Logout;
use App\Http\Controllers\RedirectToLVConnect;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ShowLogin;
use Illuminate\Routing\Router;

/* ----- Auth -------------------------------------------------------------------- */
$router->group(['middleware' => 'guest'], function (Router $router) {
    $router->get('login', ShowLogin::class)->name('login');
    $router->get('lvconnect', RedirectToLVConnect::class)->name('lvconnect');
    $router->get('login/callback', HandleSocialiteCallback::class);
});

$router->get('logout', Logout::class)->middleware('auth')->name('logout');
