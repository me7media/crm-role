<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->middleware('level:1');;

Route::group( ['middleware' => ['auth']], function() {
	Route::get('admin/profile/{id}', 'AdminController@profile')->name('admin.profile');
	Route::post('admin/profile/{id}', 'ProfileController@create');
    Route::resource('admin', 'AdminController');
    // Route::post('profile/{id}', 'ProfileController@update');
    Route::resource('profile', 'ProfileController');
// Route::post('addItem', 'ProfileController@addItem');
// Route::post('editItem', 'ProfileController@editItem');
// Route::post('deleteItem', 'ProfileController@deleteItem');
 //    Route::resource('roles', 'RoleController');
 //    Route::resource('posts', 'PostController');
	// Route::resource('permissions', 'PermissionController');
});