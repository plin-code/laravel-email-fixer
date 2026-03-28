<?php

namespace PlinCode\LaravelEmailFixer\Contracts;

interface LocalePreset
{
    /** @return FixerInterface[] */
    public function fixers(): array;

    /** @return array<string, string> */
    public function domainMap(): array;
}
