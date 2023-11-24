<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::get('/{any}', static function () {
    return view('app');
}) -> where('any', '.*');

Route::post('api/register', 'App\Http\Controllers\VocabularyController@register');
Route::post('api/list', 'App\Http\Controllers\VocabularyController@getList');
Route::post('api/list/example', 'App\Http\Controllers\VocabularyController@getExampleList');
Route::post('api/word/register', 'App\Http\Controllers\VocabularyController@add');
Route::post('api/word', 'App\Http\Controllers\VocabularyController@getItem');
Route::post('api/word/update', 'App\Http\Controllers\VocabularyController@update');
Route::post('api/word/delete', 'App\Http\Controllers\VocabularyController@delete');
Route::post('api/word/delete/example', 'App\Http\Controllers\VocabularyController@deleteExample');
Route::post('api/word/delete/meaning', 'App\Http\Controllers\VocabularyController@deleteMeaning');
Route::post('api/word/search', 'App\Http\Controllers\VocabularyController@search');