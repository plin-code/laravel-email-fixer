<?php

namespace PlinCode\LaravelEmailFixer\Fixers;

use PlinCode\LaravelEmailFixer\Contracts\DomainAwareFixer;

final class FixDomainSeparator implements DomainAwareFixer
{
    /** @param array<string, string> $domainMap */
    public function __construct(private array $domainMap) {}

    public function fix(string $email): string
    {
        $atPos = strrpos($email, '@');
        if ($atPos === false) {
            return $email;
        }

        $local = substr($email, 0, $atPos);
        $domain = substr($email, $atPos + 1);
        $normalized = str_replace(['.', '-'], '', strtolower($domain));

        foreach ($this->domainMap as $fullDomain) {
            $normalizedKnown = str_replace(['.', '-'], '', strtolower($fullDomain));
            if ($normalized === $normalizedKnown) {
                return $local.'@'.$fullDomain;
            }
        }

        return $email;
    }

    public function name(): string
    {
        return 'fix_domain_separator';
    }

    public function withDomainMap(array $domainMap): static
    {
        return new self($domainMap);
    }
}
