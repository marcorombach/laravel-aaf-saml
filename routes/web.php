<?php
Route::group(['middleware' => ['web']], function () {
    Route::post('saml-acs', [\Marcorombach\LaravelAafSAML\LaravelAafSAML::class, 'authenticate']);
    Route::get('saml-acs', [\Marcorombach\LaravelAafSAML\LaravelAafSAML::class, 'authenticate']);
    Route::get('saml-sp', [\Marcorombach\LaravelAafSAML\LaravelAafSAML::class, 'metadata']);
    Route::post('saml-slo', [\Marcorombach\LaravelAafSAML\LaravelAafSAML::class, 'logout']);
    Route::get('saml-slo', [\Marcorombach\LaravelAafSAML\LaravelAafSAML::class, 'logout']);
});

