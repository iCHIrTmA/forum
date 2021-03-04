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
Route::delete('/threads/{channel}/{thread}', 'ThreadController@destroy')->middleware('auth');
Route::get('/threads/{channel}/{thread}', 'ThreadController@show');
Route::get('/threads/{channel}/{thread}/replies', 'ReplyController@index');
Route::post('/threads/{channel}/{thread}/replies', 'ReplyController@store')->middleware('auth');
Route::patch('/replies/{reply}', 'ReplyController@update')->middleware('auth');
Route::delete('/replies/{reply}', 'ReplyController@destroy')->middleware('auth');
Route::post('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionController@store')->middleware('auth');;
Route::delete('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionController@destroy')->middleware('auth');;

Route::post('/replies/{reply}/favorites', 'FavoriteController@store')->middleware('auth');
Route::delete('/replies/{reply}/favorites', 'FavoriteController@destroy')->middleware('auth');


Route::get('/profiles/{user}', 'ProfileController@show');
Route::delete('/profiles/{user}/notifications/{notification}', 'UserNotificationController@destroy')->middleware('auth');

Auth::routes();

