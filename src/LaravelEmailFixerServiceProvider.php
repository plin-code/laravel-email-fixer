<?php

namespace PlinCode\LaravelEmailFixer;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use PlinCode\LaravelEmailFixer\Commands\LaravelEmailFixerCommand;

class LaravelEmailFixerServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-email-fixer')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel_email_fixer_table')
            ->hasCommand(LaravelEmailFixerCommand::class);
    }
}
