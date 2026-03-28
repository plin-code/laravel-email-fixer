<?php

namespace PlinCode\LaravelEmailFixer\Fixers;

use PlinCode\LaravelEmailFixer\Contracts\FixerInterface;

class CleanLocalPart implements FixerInterface
{
    public function fix(string $email): string
    {
        $atPos = strrpos($email, '@');
        if ($atPos === false) {
            return $email;
        }

        $local = rtrim(substr($email, 0, $atPos), '.');

        return $local . substr($email, $atPos);
    }

    public function name(): string
    {
        return 'clean_local_part';
    }
}
