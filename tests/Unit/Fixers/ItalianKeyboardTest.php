<?php

use PlinCode\LaravelEmailFixer\Fixers\ItalianKeyboard;

test('replaces ò with @', function () {
    $fixer = new ItalianKeyboard;

    expect($fixer->fix('marioògmail.com'))->toBe('mario@gmail.com');
});

test('replaces only the first ò', function () {
    $fixer = new ItalianKeyboard;

    expect($fixer->fix('marioògmailòcom'))->toBe('mario@gmailòcom');
});

test('is a no-op when no ò is present', function () {
    $fixer = new ItalianKeyboard;

    expect($fixer->fix('mario@gmail.com'))->toBe('mario@gmail.com');
});

test('returns the fixer name', function () {
    $fixer = new ItalianKeyboard;

    expect($fixer->name())->toBe('italian_keyboard');
});
