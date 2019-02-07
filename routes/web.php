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

Route::view('/', 'pages.home')->name('home');
Route::view('/login', 'pages.user')->name('login');

Route::post('/login', 'LoginController@initLogin')->name('setLogin');
Route::get('/logout', 'SessionController@deleteSession')->name('logout');


// API CALLS
Route::get('/user', 'LoginController@userInfoCall')->name('user');
Route::get('/repository', 'LoginController@ownRepoCall')->name('repository');

Route::get('/branches', 'LoginController@branchView')->name('branches');
Route::post('/branches', 'LoginController@deadBranchesCall')->name('getDeadBranches');

