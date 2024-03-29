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
Route::view('scan', 'scan');
Route::get('/threads', 'ThreadController@index')->name('threads');
Route::get('/threads/create', 'ThreadController@create')->middleware('auth');
Route::post('/threads', 'ThreadController@store')->middleware('auth', 'must-be-confirmed');
Route::post('/threads', 'ThreadController@store')->middleware('auth', 'must-be-confirmed');
Route::get('/threads/search', 'SearchController@show');
Route::get('/threads/{channel}', 'ThreadController@index');

Route::patch('/threads/{channel}/{thread}', 'ThreadController@update');
Route::delete('/threads/{channel}/{thread}', 'ThreadController@destroy')->middleware('auth');
Route::get('/threads/{channel}/{thread}', 'ThreadController@show');
Route::get('/threads/{channel}/{thread}/replies', 'ReplyController@index');
Route::post('/threads/{channel}/{thread}/replies', 'ReplyController@store')->middleware('auth');
Route::patch('/replies/{reply}', 'ReplyController@update')->middleware('auth');
Route::delete('/replies/{reply}', 'ReplyController@destroy')->middleware('auth')->name('replies.destroy');

Route::post('/locked-threads/{thread}', 'LockedThreadController@store')->name('locked-threads.store')->middleware('admin');
Route::delete('/locked-threads/{thread}', 'LockedThreadController@destroy')->name('locked-threads.destroy')->middleware('admin');

Route::post('/replies/{reply}/best', 'BestReplyController@store')->name('best-replies.store');

Route::post('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionController@store')->middleware('auth');;
Route::delete('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionController@destroy')->middleware('auth');;

Route::post('/replies/{reply}/favorites', 'FavoriteController@store')->middleware('auth');
Route::delete('/replies/{reply}/favorites', 'FavoriteController@destroy')->middleware('auth');


Route::get('/profiles/{user}', 'ProfileController@show')->name('profile');
Route::get('/profiles/{user}/notifications', 'UserNotificationController@index')->middleware('auth');
Route::delete('/profiles/{user}/notifications/{notification}', 'UserNotificationController@destroy')->middleware('auth');
Route::get('/register/confirm', 'Auth\RegisterConfirmationController@index')->name('register.confirm');

Route::get('/api/users', 'Api\UsersController@index');
Route::post('/api/users/{user}/avatar', 'Api\UserAvatarController@store')->middleware('auth')->name('avatar');

Auth::routes();

