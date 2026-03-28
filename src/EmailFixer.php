<?php

namespace PlinCode\LaravelEmailFixer;

use InvalidArgumentException;
use PlinCode\LaravelEmailFixer\Contracts\DomainAwareFixer;
use PlinCode\LaravelEmailFixer\Contracts\FixerInterface;
use PlinCode\LaravelEmailFixer\Contracts\LocalePreset;
use PlinCode\LaravelEmailFixer\Fixers\CleanLocalPart;
use PlinCode\LaravelEmailFixer\Fixers\CleanTrailingDots;
use PlinCode\LaravelEmailFixer\Fixers\CommaToDot;
use PlinCode\LaravelEmailFixer\Fixers\CompleteDomain;
use PlinCode\LaravelEmailFixer\Fixers\FixDomainSeparator;
use PlinCode\LaravelEmailFixer\Fixers\InsertMissingAt;
use PlinCode\LaravelEmailFixer\Fixers\Lowercase;
use PlinCode\LaravelEmailFixer\Fixers\StripAngleBrackets;
use PlinCode\LaravelEmailFixer\Fixers\StripNonAsciiTrailing;
use PlinCode\LaravelEmailFixer\Fixers\TrimWhitespace;
use PlinCode\LaravelEmailFixer\Locale\ItalianPreset;
use PlinCode\LaravelEmailFixer\Support\FixReport;

class EmailFixer
{
    /** @var array<string, class-string<LocalePreset>> */
    private static array $presets = [
        'it' => ItalianPreset::class,
    ];

    /**
     * @param FixerInterface[] $fixers
     * @param array<string, string> $domainMap
     * @param array<string, mixed> $garbageConfig
     * @param (callable(string): bool)|null $validator
     */
    public function __construct(
        private array $fixers,
        private array $domainMap,
        private array $garbageConfig = [],
        private mixed $validator = null,
    ) {}

    public function fix(string $email): string
    {
        if ($email === '') {
            return '';
        }

        foreach ($this->fixers as $fixer) {
            $email = $fixer->fix($email);
        }

        return $email;
    }

    public function fixOrNull(string $email): ?string
    {
        $fixed = $this->fix($email);

        return $this->isValidEmail($fixed) ? $fixed : null;
    }

    public function isGarbage(string $email): bool
    {
        $minLength = $this->garbageConfig['min_length'] ?? 3;
        $requireAt = $this->garbageConfig['require_at'] ?? true;
        $requireDotInDomain = $this->garbageConfig['require_dot_in_domain'] ?? true;

        if (mb_strlen($email) < $minLength) {
            return true;
        }

        if ($requireAt && ! str_contains($email, '@')) {
            return true;
        }

        if ($requireDotInDomain && str_contains($email, '@')) {
            $domain = substr($email, strrpos($email, '@') + 1);
            if (! str_contains($domain, '.')) {
                return true;
            }
        }

        return false;
    }

    public function diagnose(string $email): FixReport
    {
        $original = $email;
        $appliedFixers = [];

        foreach ($this->fixers as $fixer) {
            $result = $fixer->fix($email);
            if ($result !== $email) {
                $appliedFixers[] = $fixer->name();
                $email = $result;
            }
        }

        return new FixReport(
            original: $original,
            fixed: $email,
            appliedFixers: $appliedFixers,
            isValid: $this->isValidEmail($email),
            isGarbage: $this->isGarbage($original),
            wasModified: $original !== $email,
        );
    }

    /** @return FixReport[] */
    public function fixMany(array $emails): array
    {
        return array_map(fn (string $email) => $this->diagnose($email), $emails);
    }

    public function locale(string $locale): static
    {
        $presetClass = self::$presets[$locale] ?? null;

        if ($presetClass === null) {
            throw new InvalidArgumentException("Unknown locale preset: {$locale}");
        }

        /** @var LocalePreset $preset */
        $preset = new $presetClass;
        $mergedMap = array_merge($this->domainMap, $preset->domainMap());

        $fixers = [];
        foreach ($this->fixers as $fixer) {
            if ($fixer instanceof DomainAwareFixer) {
                $fixers[] = $fixer->withDomainMap($mergedMap);
            } else {
                $fixers[] = $fixer;
            }
        }

        // Insert preset fixers before the first DomainAwareFixer
        $insertIndex = count($fixers);
        foreach ($fixers as $i => $fixer) {
            if ($fixer instanceof DomainAwareFixer) {
                $insertIndex = $i;
                break;
            }
        }

        array_splice($fixers, $insertIndex, 0, $preset->fixers());

        return new static($fixers, $mergedMap, $this->garbageConfig, $this->validator);
    }

    /**
     * @param array<string, string> $domainMap
     * @param array<string, mixed> $garbageConfig
     * @param (callable(string): bool)|null $validator
     */
    public static function defaults(
        array $domainMap = [],
        array $garbageConfig = [],
        ?callable $validator = null,
    ): static {
        return new static(
            fixers: self::defaultFixers($domainMap),
            domainMap: $domainMap,
            garbageConfig: $garbageConfig,
            validator: $validator,
        );
    }

    /** @return FixerInterface[] */
    private static function defaultFixers(array $domainMap): array
    {
        return [
            new TrimWhitespace,
            new StripAngleBrackets,
            new StripNonAsciiTrailing,
            new CommaToDot,
            new InsertMissingAt($domainMap),
            new FixDomainSeparator($domainMap),
            new CompleteDomain($domainMap),
            new CleanLocalPart,
            new CleanTrailingDots,
            new Lowercase,
        ];
    }

    private function isValidEmail(string $email): bool
    {
        if ($this->validator !== null) {
            return ($this->validator)($email);
        }

        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}
