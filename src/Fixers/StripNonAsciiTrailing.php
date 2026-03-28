<?php

namespace PlinCode\LaravelEmailFixer\Fixers;

use PlinCode\LaravelEmailFixer\Contracts\FixerInterface;

class StripNonAsciiTrailing implements FixerInterface
{
    public function fix(string $email): string
    {
        return preg_replace('/[^\x20-\x7E]+$/', '', $email);
    }

    public function name(): string
    {
        return 'strip_non_ascii_trailing';
    }
}
