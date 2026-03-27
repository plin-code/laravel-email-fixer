<?php

namespace PlinCode\LaravelEmailFixer\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \PlinCode\LaravelEmailFixer\LaravelEmailFixer
 */
class LaravelEmailFixer extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \PlinCode\LaravelEmailFixer\LaravelEmailFixer::class;
    }
}
