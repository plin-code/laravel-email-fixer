<?php

namespace PlinCode\LaravelEmailFixer\Fixers;

use PlinCode\LaravelEmailFixer\Contracts\FixerInterface;

class Lowercase implements FixerInterface
{
    public function fix(string $email): string
    {
        return mb_strtolower($email);
    }

    public function name(): string
    {
        return 'lowercase';
    }
}
