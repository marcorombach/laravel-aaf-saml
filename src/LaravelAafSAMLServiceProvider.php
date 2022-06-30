<?php

namespace Marcorombach\LaravelAafSAML;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelAafSAMLServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-aaf-saml')
            ->hasViews('views')
            ->hasRoute('web')
            ->hasConfigFile();
    }
}
