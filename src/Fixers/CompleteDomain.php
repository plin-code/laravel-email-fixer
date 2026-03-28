<?php

namespace PlinCode\LaravelEmailFixer\Fixers;

use PlinCode\LaravelEmailFixer\Contracts\DomainAwareFixer;

final class CompleteDomain implements DomainAwareFixer
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

        if (isset($this->domainMap[$domain])) {
            return $local.'@'.$this->domainMap[$domain];
        }

        return $email;
    }

    public function name(): string
    {
        return 'complete_domain';
    }

    public function withDomainMap(array $domainMap): static
    {
        return new self($domainMap);
    }
}
