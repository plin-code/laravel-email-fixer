<?php

use PlinCode\LaravelEmailFixer\Fixers\TrimWhitespace;

test('removes leading and trailing whitespace', function () {
    $fixer = new TrimWhitespace;

    expect($fixer->fix('  user@example.com  '))->toBe('user@example.com');
});

test('removes internal spaces', function () {
    $fixer = new TrimWhitespace;

    expect($fixer->fix('user @example .com'))->toBe('user@example.com');
});

test('removes tabs and newlines', function () {
    $fixer = new TrimWhitespace;

    expect($fixer->fix("user\t@example\n.com"))->toBe('user@example.com');
});

test('returns the fixer name', function () {
    $fixer = new TrimWhitespace;

    expect($fixer->name())->toBe('trim_whitespace');
});
