<?php

namespace PlinCode\LaravelEmailFixer\Contracts;

interface DomainAwareFixer extends FixerInterface
{
    /** @param array<string, string> $domainMap */
    public function withDomainMap(array $domainMap): static;
}
