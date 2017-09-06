<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', 'SiteController@index');
Route::get('/search', 'SiteController@search');
Route::get('/categoria/{slug}', 'SiteController@categoria');
Route::get('/subcategoria/{slug}', 'SiteController@subcategoria');
Route::get('/empresa/{id}/{slug}', 'SiteController@empresa');
Route::get('/registro_empresa', 'SiteController@registro_empresa');
Route::post('/comentar', 'SiteController@comentar');

// Estaticos
Route::get('/nosotros', function () { return view('estatic.nosotros'); });
Route::get('/politicas_de_privacidad', function () { return view('estatic.politicas'); });
Route::get('/terminos', function () { return view('estatic.terminos'); });
Route::get('/anuncie', function () { return view('estatic.anuncie'); });
Route::get('/contacto', function () { return view('estatic.contacto'); });

// social
Route::get('/redirect/{provider}','SocialAuthController@redirect');
Route::get('/callback/{provider}','SocialAuthController@callback');


//Route::get('/', function () {
//    return view('welcome');
//});

Auth::routes();

Route::get('/home', 'SiteController@index');
