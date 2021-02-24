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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/threads', 'ThreadController@index');
Route::get('/threads/create', 'ThreadController@create')->middleware('auth');
Route::post('/threads', 'ThreadController@store')->middleware('auth');
Route::get('/threads/{channel}', 'ThreadController@index');
Route::get('/threads/{channel}/{thread}', 'ThreadController@show');
Route::delete('/threads/{channel}/{thread}', 'ThreadController@destroy')->middleware('auth');
Route::post('/threads/{channel}/{thread}/replies', 'ReplyController@store')->middleware('auth');
Route::post('/replies/{reply}/favorites', 'FavoriteController@store')->middleware('auth');

Route::get('/profiles/{user}', 'ProfileController@show');

Auth::routes();

