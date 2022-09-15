# laravel-aaf-saml

This Laravel Package provides a simple way to authenticate with SAML2.
Minimum requirement is a User Model/Table which has either a field 'username' or a field 'email'.
It's recommended to define a post login route and a error route.
The error route is called with a flashed session variable (session('error')) containing information to display.

To configure this package with NetIQ Advanced Authentication, a Event must be created.

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

It's not necessary to use the class directly. Laravel-AAF-SAML provides a route which starts the authentication process.

```
/saml-login
```

**Please note**: your application needs to use HTTPS, as it's a requirement of the NetIQ Advanced Authentication Framework.

## Requirements

- PHP 7.4 or greater
- Laravel 8.0 or greater

## Credits

- [OneLogin - SAML PHP Toolkit](https://github.com/onelogin/php-saml)
- [Marco Rombach](https://github.com/marcorombach)
