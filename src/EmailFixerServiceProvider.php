<?php

namespace PlinCode\LaravelEmailFixer;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class EmailFixerServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('email-fixer')
            ->hasConfigFile('email-fixer');
    }
}
