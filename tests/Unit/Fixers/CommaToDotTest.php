<?php

use PlinCode\LaravelEmailFixer\Fixers\CommaToDot;

test('replaces a comma in the domain part with a dot', function () {
    $fixer = new CommaToDot;

    expect($fixer->fix('user@example,com'))->toBe('user@example.com');
});

test('replaces a comma in the local part with a dot', function () {
    $fixer = new CommaToDot;

    expect($fixer->fix('first,last@example.com'))->toBe('first.last@example.com');
});

test('replaces multiple commas', function () {
    $fixer = new CommaToDot;

    expect($fixer->fix('first,last@example,com'))->toBe('first.last@example.com');
});

test('is a no-op when no commas are present', function () {
    $fixer = new CommaToDot;

    expect($fixer->fix('user@example.com'))->toBe('user@example.com');
});

test('returns the fixer name', function () {
    $fixer = new CommaToDot;

    expect($fixer->name())->toBe('comma_to_dot');
});
