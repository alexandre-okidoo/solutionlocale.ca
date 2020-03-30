<?php

use Illuminate\Support\Facades\Route;
use Spatie\Honeypot\ProtectAgainstSpam;

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

Route::middleware(['auth', 'can:access-backend'])->group(function () {
    Route::get('/home', 'HomeController@index')->name('home');

    Route::get('/user/{user}', 'UserController@edit')->name('users.edit');
    Route::put('/user/{user}', 'UserController@update')->name('users.update');

    Route::get('/distribution/ajout', 'DeliveryTypeController@create')->name('deliveryTypes.create')->middleware('can:do-admin');
    Route::post('/distribution', 'DeliveryTypeController@store')->name('deliveryTypes.store')->middleware('can:do-admin');

    Route::get('/moderation', 'ModerationController@index')->name('moderation.index')->middleware('can:do-moderation');
    Route::get('/moderation/{region:slug}', 'ModerationController@show')->name('moderation.show')->middleware('can:do-moderation');
    Route::get('/moderation/place/{place:slug}/approbation', 'ModerationController@store')->name('moderation.approve')->middleware('can:do-moderation');
    Route::get('/moderation/places/{place:slug}/enlever', 'ModerationController@delete')->name('moderation.delete')->middleware('can:do-moderation');
    Route::post('/moderation/places/{place:slug}/enlever', 'ModerationController@destroy')->name('moderation.destroy')->middleware('can:do-moderation');

    Route::get('/places/ajout', 'PlaceController@create')->name('places.create')->middleware('can:do-moderation');
    Route::post('/places', 'PlaceController@store')->name('places.store')->middleware('can:do-moderation');
    Route::get('/places/{place:slug}/modifier', 'PlaceController@edit')->name('places.edit')->middleware('can:do-moderation');
    Route::put('/places/{place:slug}', 'PlaceController@update')->name('places.update')->middleware('can:do-moderation');

    Route::get('/categorie/ajout', 'CategoryController@create')->name('categories.create')->middleware('can:do-admin');
    Route::post('/categorie', 'CategoryController@store')->name('categories.store')->middleware('can:do-admin');
});

Auth::routes(['register' => false]);
Route::get('/categorie/{category:slug}', 'CategoryController@index')->name('categories.index');

Route::get('/region/province', 'PublicController@indexProvincial')->name('public.index-provincial');
Route::get('/region/{region:slug}', 'PublicController@indexRegional')->name('public.index-region');
Route::get('/region/{region:slug}/{category}', 'PublicController@indexRegionalCategories')->name('public.index-region-category');
Route::get('/entreprise/ajout', 'PlaceController@createPublic')->name('places.create-public');
Route::post('/entreprise/ajout', 'PlaceController@storePublic')->name('places.store-public')->middleware(ProtectAgainstSpam::class);
Route::get('/entreprise/{place:slug}', 'PlaceController@show')->name('places.show');
Route::get('/', 'PublicController@index')->name('public.index');
