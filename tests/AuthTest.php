<?php

use Marcorombach\LaravelAafSAML\LaravelAafSAML;

test('can login', function () {
    $login = new LaravelAafSAML();
    if ($login->authenticate(config('aaf-saml.testuser'), config('aaf-saml.testpassword'))) {
        $this->assertTrue(true);
    } else {
        $this->assertTrue(false);
    }
});
