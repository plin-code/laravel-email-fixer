<?php

namespace PlinCode\LaravelEmailFixer\Fixers;

use PlinCode\LaravelEmailFixer\Contracts\DomainAwareFixer;

class InsertMissingAt implements DomainAwareFixer
{
    /** @param array<string, string> $domainMap */
    public function __construct(private array $domainMap) {}

    public function fix(string $email): string
    {
        if (str_contains($email, '@')) {
            return $email;
        }

        // Check full domain values first (longest match wins)
        $fullDomains = array_unique(array_values($this->domainMap));
        usort($fullDomains, fn (string $a, string $b) => strlen($b) - strlen($a));

        foreach ($fullDomains as $domain) {
            if (str_ends_with($email, $domain)) {
                $local = substr($email, 0, -strlen($domain));
                if ($local !== '' && $local !== false) {
                    return $local . '@' . $domain;
                }
            }
        }

        // Check short domain keys (longest match wins)
        $keys = array_keys($this->domainMap);
        usort($keys, fn (string $a, string $b) => strlen($b) - strlen($a));

        foreach ($keys as $key) {
            if (str_ends_with($email, $key)) {
                $local = substr($email, 0, -strlen($key));
                if ($local !== '' && $local !== false) {
                    return $local . '@' . $this->domainMap[$key];
                }
            }
        }

        return $email;
    }

    public function name(): string
    {
        return 'insert_missing_at';
    }

    public function withDomainMap(array $domainMap): static
    {
        return new static($domainMap);
    }
}
