<?php

namespace PlinCode\LaravelEmailFixer\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use PlinCode\LaravelEmailFixer\EmailFixer;

class SanitizedEmail implements ValidationRule
{
    public function __construct(
        private ?string $locale = null,
        private bool $strict = false,
        private bool $rejectGarbage = false,
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $fixer = app(EmailFixer::class);

        if ($this->locale) {
            $fixer = $fixer->locale($this->locale);
        }

        if ($this->rejectGarbage && $fixer->isGarbage($value)) {
            $fail('The :attribute is not a valid email address.');

            return;
        }

        $fixed = $fixer->fix($value);

        request()?->merge([$attribute => $fixed]);

        $emailRule = $this->strict ? 'email:rfc' : 'email';
        $validator = validator([$attribute => $fixed], [$attribute => $emailRule]);

        if ($validator->fails()) {
            $fail('The :attribute is not a valid email address.');
        }
    }
}
