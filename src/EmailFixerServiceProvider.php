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

    public function packageRegistered(): void
    {
        $this->app->singleton(EmailFixer::class, function ($app) {
            $config = $app['config']['email-fixer'] ?? [];

            $domainMap = $config['domains'] ?? [];
            $garbageConfig = $config['garbage'] ?? [];
            $locale = $config['locale'] ?? null;

            $fixer = EmailFixer::defaults(
                domainMap: $domainMap,
                garbageConfig: $garbageConfig,
                validator: fn (string $email) => filter_var($email, FILTER_VALIDATE_EMAIL) !== false,
            );

            if ($locale !== null) {
                $fixer = $fixer->locale($locale);
            }

            return $fixer;
        });
    }
}
