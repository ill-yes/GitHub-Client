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


Route::view('/', 'pages.home')->name('home');

Route::get('/login/github', 'SocialiteController@redirect')->name('login');
Route::get('/login/github/callback', 'SocialiteController@handleCallback');

Route::post('/logout', 'SocialiteController@logout')->name('logout');


// API CALLS
Route::get('/branches', 'HomeController@branchView')->name('branches');
Route::post('/branches', 'HomeController@deadBranchesCall')->name('getDeadBranches');

Route::get('/pr-location', 'HomeController@prLocationCall')->name('pr-location');
