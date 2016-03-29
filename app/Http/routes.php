<?php

Route::group(['middleware' => 'web'], function () {
    Route::auth();

    Route::get('/', 'CalendarController@index');

    Route::get('/calendar-google-events', 'CalendarController@events');

    $s = 'social.';
	Route::get('/social/redirect/{provider}',   ['as' => $s . 'redirect',   'uses' => 'Auth\AuthController@getSocialRedirect']);
	Route::get('/social/handle/{provider}',     ['as' => $s . 'handle',     'uses' => 'Auth\AuthController@getSocialHandle']);
});
