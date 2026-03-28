<?php

namespace PlinCode\LaravelEmailFixer\Fixers;

use PlinCode\LaravelEmailFixer\Contracts\FixerInterface;

class TrimWhitespace implements FixerInterface
{
    public function fix(string $email): string
    {
        return preg_replace('/\s+/', '', $email);
    }

    public function name(): string
    {
        return 'trim_whitespace';
    }
}
