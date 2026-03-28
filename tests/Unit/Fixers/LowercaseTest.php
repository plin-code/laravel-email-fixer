<?php

use PlinCode\LaravelEmailFixer\Fixers\Lowercase;

test('lowercases all-caps email', function () {
    $fixer = new Lowercase;

    expect($fixer->fix('USER@EXAMPLE.COM'))->toBe('user@example.com');
});

test('handles mixed case', function () {
    $fixer = new Lowercase;

    expect($fixer->fix('User@Example.Com'))->toBe('user@example.com');
});

test('is a no-op for already lowercase', function () {
    $fixer = new Lowercase;

    expect($fixer->fix('user@example.com'))->toBe('user@example.com');
});

test('returns the fixer name', function () {
    $fixer = new Lowercase;

    expect($fixer->name())->toBe('lowercase');
});
