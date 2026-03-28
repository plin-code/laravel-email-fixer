<?php

namespace PlinCode\LaravelEmailFixer\Locale;

use PlinCode\LaravelEmailFixer\Contracts\LocalePreset;
use PlinCode\LaravelEmailFixer\Fixers\ItalianKeyboard;

class ItalianPreset implements LocalePreset
{
    public function fixers(): array
    {
        return [
            new ItalianKeyboard,
        ];
    }

    public function domainMap(): array
    {
        return [
            'libero' => 'libero.it',
            'virgilio' => 'virgilio.it',
            'alice' => 'alice.it',
            'tiscali' => 'tiscali.it',
            'pec' => 'pec.it',
            'live' => 'live.it',
            'tim' => 'tim.it',
            'email' => 'email.it',
            'fastwebnet' => 'fastwebnet.it',
            'aruba' => 'aruba.it',
        ];
    }
}
