<?php

use PlinCode\LaravelEmailFixer\Fixers\CleanLocalPart;

test('removes trailing dot before @', function () {
    $fixer = new CleanLocalPart;

    expect($fixer->fix('user.@example.com'))->toBe('user@example.com');
});

test('removes multiple trailing dots before @', function () {
    $fixer = new CleanLocalPart;

    expect($fixer->fix('user...@example.com'))->toBe('user@example.com');
});

test('is a no-op for clean local parts', function () {
    $fixer = new CleanLocalPart;

    expect($fixer->fix('user@example.com'))->toBe('user@example.com');
});

test('returns unchanged when no @ is present', function () {
    $fixer = new CleanLocalPart;

    expect($fixer->fix('userexample.com'))->toBe('userexample.com');
});

test('returns the fixer name', function () {
    $fixer = new CleanLocalPart;

    expect($fixer->name())->toBe('clean_local_part');
});
