<?php

namespace PlinCode\LaravelEmailFixer\Fixers;

use PlinCode\LaravelEmailFixer\Contracts\FixerInterface;

class ItalianKeyboard implements FixerInterface
{
    public function fix(string $email): string
    {
        $pos = mb_strpos($email, 'ò');
        if ($pos === false) {
            return $email;
        }

        return mb_substr($email, 0, $pos) . '@' . mb_substr($email, $pos + 1);
    }

    public function name(): string
    {
        return 'italian_keyboard';
    }
}
