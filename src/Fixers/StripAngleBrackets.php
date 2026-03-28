<?php

namespace PlinCode\LaravelEmailFixer\Fixers;

use PlinCode\LaravelEmailFixer\Contracts\FixerInterface;

class StripAngleBrackets implements FixerInterface
{
    public function fix(string $email): string
    {
        if (preg_match('/^<(.+)>$/', $email, $matches)) {
            return $matches[1];
        }

        return $email;
    }

    public function name(): string
    {
        return 'strip_angle_brackets';
    }
}
