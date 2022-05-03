# laravel-aaf-saml

 --  TODO --

## Installation

Install the package via composer:

```bash
composer require marcorombach/laravel-aaf-saml
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-aaf-saml-config"
```

This is the contents of the published config file:

```php
return [

];
```


## Usage

```php
$laravelAafSAML = new Marcorombach\LaravelAafSAML();
$authenticatable = $laravelAafSAML->authenticate('username', 'password');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Marco Rombach](https://github.com/marcorombach)
