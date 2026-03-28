<?php

namespace PlinCode\LaravelEmailFixer\Fixers;

use PlinCode\LaravelEmailFixer\Contracts\FixerInterface;

class CommaToDot implements FixerInterface
{
    public function fix(string $email): string
    {
        return str_replace(',', '.', $email);
    }

    public function name(): string
    {
        return 'comma_to_dot';
    }
}
