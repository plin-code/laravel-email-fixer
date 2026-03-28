<?php

use PlinCode\LaravelEmailFixer\Fixers\CleanTrailingDots;

test('removes trailing dot', function () {
    $fixer = new CleanTrailingDots;

    expect($fixer->fix('user@example.com.'))->toBe('user@example.com');
});

test('removes multiple trailing dots', function () {
    $fixer = new CleanTrailingDots;

    expect($fixer->fix('user@example.com...'))->toBe('user@example.com');
});

test('is a no-op for clean emails', function () {
    $fixer = new CleanTrailingDots;

    expect($fixer->fix('user@example.com'))->toBe('user@example.com');
});

test('returns the fixer name', function () {
    $fixer = new CleanTrailingDots;

    expect($fixer->name())->toBe('clean_trailing_dots');
});
