# laravel-aaf-saml

This Laravel Package provides a simple way to authenticate with SAML2.
Minimum requirement is a User Model/Table which has either a field 'username' or a field 'email'.
It's recommended to define a post login route and a error route.
The error route is called with a flashed session variable (session('error')) containing information to display.

To configure this package with NetIQ Advanced Authentication, a Event must be created.
-- TODO --

## Installation

Install the package via composer:

```bash
composer require marcorombach/laravel-aaf-saml
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="aaf-saml-config"
```

This is the contents of the published config file:

```php
return [
    'service_name' => '',
    'service_description' => '',
    'idpmetadataurl' => '',
    'post-login-route' => '', //Route to redirect to after login - if not set you will be redirected to the base URL
    'error-route' => '', //Route to redirect to on login error
];
```


## Usage

```php
$laravelAafSAML = new Marcorombach\LaravelAafSAML();
$authenticatable = $laravelAafSAML->authenticate('username', 'password');
```


## Credits

- [Marco Rombach](https://github.com/marcorombach)
