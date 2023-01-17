<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;

use App\Models\Group;
use App\Models\User;
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
Route::get('/', function(){
    return view('clients.login');
 })->name('home');

Route::post('/login', LoginController::class)->name('login');

Route::resource('user', UserController::class)->only('index');
Route::resource('user', UserController::class)->middleware('checklogin::class')->except('index');

// Route::prefix('users')->name('users.')->group(function () {
//     Route::get('/', [UserController::class, 'index'])->name('index');
//     Route::group(['middleware' => 'checklogin'], function () {
//         Route::get('/add', [UserController::class, 'add'])->name('add');
//         Route::post('/add', [UserController::class, 'postAdd'])->name('post-add');
//         Route::get('/edit/{id}', [UserController::class, 'getEdit'])->name('edit');
//         Route::post('/update', [UserController::class, 'postEdit'])->name('post-edit');
//         Route::get('/delete/{id}', [UserController::class, 'delete'])->name('delete');
//     });
// });


// Test
// Route::get('profile/{user}', function (User $user) {
//     return $user->name;
// });

Route::get('profile/{user}/{group}', function (User $user, Group $group) {
    return $group;
});

Route::get('profile/{name?}', function ($name = 'hana') {
    return $name;
});
// ->where('name', '[a-z]+');

Route::get('search/{search}', function ($search) {
    return $search;
})->where('search', '.*');

Route::get('test/{user}', function ($user) {
    return $user;
});
