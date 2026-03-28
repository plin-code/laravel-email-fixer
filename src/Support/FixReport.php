<?php

namespace PlinCode\LaravelEmailFixer\Support;

class FixReport
{
    /**
     * @param  string[]  $appliedFixers
     */
    public function __construct(
        public readonly string $original,
        public readonly string $fixed,
        public readonly array $appliedFixers,
        public readonly bool $isValid,
        public readonly bool $isGarbage,
        public readonly bool $wasModified,
    ) {}
}
