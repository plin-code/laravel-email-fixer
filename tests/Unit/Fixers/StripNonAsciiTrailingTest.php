<?php

use PlinCode\LaravelEmailFixer\Fixers\StripNonAsciiTrailing;

test('removes trailing non-ASCII characters (zero-width space)', function () {
    $fixer = new StripNonAsciiTrailing;

    // U+200B zero-width space appended as encoding artifact
    expect($fixer->fix("user@example.com\u{200B}"))->toBe('user@example.com');
});

test('is a no-op for a clean ASCII email', function () {
    $fixer = new StripNonAsciiTrailing;

    expect($fixer->fix('user@example.com'))->toBe('user@example.com');
});

test('preserves non-ASCII characters in the middle of the string', function () {
    $fixer = new StripNonAsciiTrailing;

    // ò in the local part should remain untouched
    expect($fixer->fix("user\xF2@example.com"))->toBe("user\xF2@example.com");
});

test('returns the fixer name', function () {
    $fixer = new StripNonAsciiTrailing;

    expect($fixer->name())->toBe('strip_non_ascii_trailing');
});
