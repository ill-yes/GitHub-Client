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

use Illuminate\Support\Facades\Route;


//Route::get('login/github', 'AuthController@redirectToProvider')->name("login");
//Route::get('login/github/callback', 'AuthController@handleProviderCallback')->name("auth.callback");

Route::view('/', 'pages.home')->name('home');

Route::get('/login/github', 'SocialiteController@redirect')->name("login");
Route::get('/login/github/callback', 'SocialiteController@handleCallback');

Route::post('/logout', 'SocialiteController@logout')->name('logout');
//Route::get('/logout', 'SocialiteController@logout')->name('logout');

//Route::get('/', 'LoginController@initLogin');


//Route::view('/', 'pages.home')->name('home');
//Route::view('/login', 'pages.user')->name('login');

//Route::post('/login', 'AuthController@initLogin')->name('setLogin');
//Route::get('/logout', 'SessionController@deleteSession')->name('logout');


// API CALLS
//Route::get('/user', 'AuthController@userInfoCall')->name('user');
//Route::get('/repository', 'AuthController@ownRepoCall')->name('repository');

Route::get('/branches', 'LoginController@branchView')->name('branches');
Route::post('/branches', 'LoginController@deadBranchesCall')->name('getDeadBranches');

Route::get('/pr-location', 'LoginController@prLocationCall')->name('pr-location');

//Auth::routes();
//
//Route::get('/home', 'HomeController@index')->name('home');
//
//Auth::routes();
//
//Route::get('/home', 'HomeController@index')->name('home');
