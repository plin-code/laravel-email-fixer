<?php

namespace PlinCode\LaravelEmailFixer\Contracts;

interface FixerInterface
{
    public function fix(string $email): string;

    public function name(): string;
}
