<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::GET('/media', function () {
    return view('media');
});
//Route::get('/{any}', function() {
//    return view('mvs.index');
//})->where('any','.*');

Route::GET(     '/',                                    [App\Http\Controllers\MvsController::class,'index'])->name('index');
Route::GET(     '/pc',                                  [App\Http\Controllers\MvsController::class,'pc'])->name('pc');

/*********************
 ** AUTH
 **********************/
Route::POST(    '/find',                                [App\Http\Controllers\AuthController::class,'find'])->name('auth.find');
Route::GET(     '/admin',                               [App\Http\Controllers\AuthController::class,'index'])->name('login');
Route::GET(    '/login',                                [App\Http\Controllers\AuthController::class,'index'])->name('auth.index');
Route::POST(    '/login',                               [App\Http\Controllers\AuthController::class,'login'])->name('auth.login');
Route::POST(    '/logout',                              [App\Http\Controllers\AuthController::class,'logout'])->name('auth.logout');


/*********************
 ** USERS
 **********************/
Route::GET( '/users',                                   [App\Http\Controllers\UserController::class,'index'])->name('users.index');
Route::GET( '/users/create',                            [App\Http\Controllers\UserController::class,'create'])->name('users.create');
Route::POST('/users',                                   [App\Http\Controllers\UserController::class,'store'])->name('users.store');


/*********************
 ** CUSTOMERS
 **********************/
Route::GET( '/customers',                               [App\Http\Controllers\CustomerController::class,'index'])->name('customers.index');
Route::POST( '/customers',                              [App\Http\Controllers\CustomerController::class,'store'])->name('customers.store');
Route::GET( '/customers/create',                        [App\Http\Controllers\CustomerController::class,'create'])->name('customers.create');
Route::GET( '/customers/{id}/edit',                     [App\Http\Controllers\CustomerController::class,'edit'])->name('customers.edit');
Route::PATCH( '/customers/{id}',                        [App\Http\Controllers\CustomerController::class,'update'])->name('customers.update');
Route::DELETE( '/customers/{id}',                       [App\Http\Controllers\CustomerController::class,'destroy'])->name('customers.destroy');

/* customer_video*/
Route::GET( '/customers/{id}/video',                    [App\Http\Controllers\CustomerController::class,'video'])->name('customers.video');
Route::GET( '/customers/{id}/view',                    [App\Http\Controllers\CustomerController::class,'view'])->name('customers.view');
Route::POST( '/customers/video',                        [App\Http\Controllers\CustomerController::class,'videoStore'])->name('customers.videoStore');
Route::DELETE( '/customers/video/{id}',                 [App\Http\Controllers\CustomerController::class,'videoDestroy'])->name('customers.videoDestroy');


/*********************
 ** MVS
 **********************/
Route::GET('/dashboard',                                [App\Http\Controllers\MvsController::class,'dashboard'])->name('dashboard');
Route::GET('/video',                                    [App\Http\Controllers\MvsController::class,'retrieve']);
Route::GET('/flag',                                     [App\Http\Controllers\MvsController::class,'flag']);
Route::GET('/playback-management',                      [App\Http\Controllers\PlaybackManagementController::class,'index'])->middleware('auth')->name('playback-management.index');
Route::GET('/playback-management/search',               [App\Http\Controllers\PlaybackManagementController::class,'search'])->middleware('auth')->name('playback-management.search');
Route::POST('/playback-management/play',                [App\Http\Controllers\PlaybackManagementController::class,'play'])->middleware('auth')->name('playback-management.play');
Route::POST('/terminal/status',                         [App\Http\Controllers\PlaybackManagementController::class,'terminalStatus'])->name('terminal.status');

/*********************
 ** VIDEO
 **********************/
Route::GET( '/videos',                                  [App\Http\Controllers\VideoController::class,'index'])->name('videos.index');
Route::POST( '/videos',                                 [App\Http\Controllers\VideoController::class,'store'])->name('videos.store');
Route::GET( '/videos/create',                           [App\Http\Controllers\VideoController::class,'create'])->name('videos.create');
Route::GET( '/videos/{id}/edit',                        [App\Http\Controllers\VideoController::class,'edit'])->name('videos.edit');
Route::PATCH( '/videos/{id}',                           [App\Http\Controllers\VideoController::class,'update'])->name('videos.update');
Route::DELETE( '/videos/{id}',                          [App\Http\Controllers\VideoController::class,'destroy'])->name('videos.destroy');

Route::GET( '/videos/test/destroy',                     [App\Http\Controllers\VideoController::class,'destroyTest'])->name('videos.destroyTest');

Route::GET( '/phpinfo',                                 [App\Http\Controllers\MvsController::class,'phpInfo']);


