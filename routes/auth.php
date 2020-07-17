<?php

Route::get('/login', ['as'=>'wayf_login','uses' => 'Auth\Saml2Controller@wayf']);
Route::any('/idp/demo', ['uses' => 'Auth\DemoController@list']);
Route::get('/idp/google', 'Auth\Saml2Controller@google_redirect');
Route::get('/idp/google/callback', 'Auth\Saml2Controller@google_callback');
Route::get('/logout', ['as' => 'saml_logout','uses' => 'Auth\Saml2Controller@logout']);

/* SAML Stuff */
Route::group(['prefix' => '/saml2','middleware' => ['saml']], function () {
    Route::get('/metadata',['as' => 'saml_metadata','uses' => 'Auth\Saml2Controller@metadata']);
    Route::post('/acs',['as' => 'saml_acs','uses' => 'Auth\Saml2Controller@acs']);
    Route::get('/sls',['as' => 'saml_sls','uses' => 'Auth\Saml2Controller@sls']);
    Route::get('/wayf/{site}', ['as' => 'saml_wayf','uses' => 'Auth\Saml2Controller@wayfcallback']);
});

/* API STUFF */
Route::group(['prefix' => 'api','middleware'=>['no.save.session']], function () use ($router) {
    Route::get('/idps',['uses'=>'Auth\Saml2Controller@idps']);
});