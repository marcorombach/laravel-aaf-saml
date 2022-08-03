<?php
Route::group(['middleware' => ['web']], function () {
    Route::post('saml-acs', [\Marcorombach\LaravelAafSAML\LaravelAafSAML::class, 'authenticate'])->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
    Route::get('saml-login', [\Marcorombach\LaravelAafSAML\LaravelAafSAML::class, 'initiate']);
    Route::get('saml-sp', [\Marcorombach\LaravelAafSAML\LaravelAafSAML::class, 'metadata']);
    //Route::get('saml-slo', [\Marcorombach\LaravelAafSAML\LaravelAafSAML::class, 'logout']);
});

