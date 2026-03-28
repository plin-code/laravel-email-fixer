<?php

use PlinCode\LaravelEmailFixer\Fixers\StripAngleBrackets;

test('removes wrapping angle brackets', function () {
    $fixer = new StripAngleBrackets;

    expect($fixer->fix('<user@example.com>'))->toBe('user@example.com');
});

test('is a no-op when no angle brackets present', function () {
    $fixer = new StripAngleBrackets;

    expect($fixer->fix('user@example.com'))->toBe('user@example.com');
});

test('is a no-op with only a leading angle bracket', function () {
    $fixer = new StripAngleBrackets;

    expect($fixer->fix('<user@example.com'))->toBe('<user@example.com');
});

test('is a no-op with only a trailing angle bracket', function () {
    $fixer = new StripAngleBrackets;

    expect($fixer->fix('user@example.com>'))->toBe('user@example.com>');
});

test('returns the fixer name', function () {
    $fixer = new StripAngleBrackets;

    expect($fixer->name())->toBe('strip_angle_brackets');
});
