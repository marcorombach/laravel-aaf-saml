<?php

namespace Marcorombach\LaravelAafSAML;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Marcorombach\LaravelAafRadius\Commands\LaravelAafSAMLCommand;

class LaravelAafSAMLServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-aaf-saml')
            ->hasRoute('web')
            ->hasConfigFile();
    }
}
