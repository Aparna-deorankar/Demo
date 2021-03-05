<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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

// Route::get('/', 'MainCotroller@index');
Route::get('/success', 'MainCotroller@success');
Route::post('/payment', 'MainCotroller@payment');
Route::post('/pay', 'MainCotroller@pay');

Route::get('/payment-initiate','PaymentController@index');
Route::post('/payment-initiate-request','PaymentController@initiate');
Route::post('/payment-complete','PaymentController@complete');

Route::resource('ajax-crud', 'AjaxCrudController');

Route::post('ajax-crud/update', 'AjaxCrudController@update')->name('ajax-crud.update');

Route::get('ajax-crud/destroy/{id}', 'AjaxCrudController@destroy');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// GOOGLE LOGIN
Route::get('login/google', 'Auth\LoginController@redirectToGoogle')->name('login.google');
Route::get('login/google/callback', 'Auth\LoginController@handleGoogleCallback');

// facebook login
Route::get('login/facebook', 'Auth\LoginController@redirectToFacebook')->name('login.facebook');
Route::get('login/facebook/callback', 'Auth\LoginController@handleFacebookCallback');

//github login
Route::get('login/github', 'Auth\LoginController@redirectToGithub')->name('login.github');
Route::get('login/github/callback', 'Auth\LoginController@handleGithubCallback');
