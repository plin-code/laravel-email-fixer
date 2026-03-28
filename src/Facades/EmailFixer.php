<?php

namespace PlinCode\LaravelEmailFixer\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string fix(string $email)
 * @method static string|null fixOrNull(string $email)
 * @method static bool isGarbage(string $email)
 * @method static \PlinCode\LaravelEmailFixer\Support\FixReport diagnose(string $email)
 * @method static \PlinCode\LaravelEmailFixer\Support\FixReport[] fixMany(array $emails)
 * @method static \PlinCode\LaravelEmailFixer\EmailFixer locale(string $locale)
 *
 * @see \PlinCode\LaravelEmailFixer\EmailFixer
 */
class EmailFixer extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \PlinCode\LaravelEmailFixer\EmailFixer::class;
    }
}
