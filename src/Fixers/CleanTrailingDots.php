<?php

namespace PlinCode\LaravelEmailFixer\Fixers;

use PlinCode\LaravelEmailFixer\Contracts\FixerInterface;

class CleanTrailingDots implements FixerInterface
{
    public function fix(string $email): string
    {
        return rtrim($email, '.');
    }

    public function name(): string
    {
        return 'clean_trailing_dots';
    }
}
