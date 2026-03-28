<?php

use PlinCode\LaravelEmailFixer\Contracts\DomainAwareFixer;
use PlinCode\LaravelEmailFixer\Fixers\CompleteDomain;

beforeEach(function () {
    $this->fixer = new CompleteDomain([
        'gmail' => 'gmail.com',
        'yahoo' => 'yahoo.com',
        'proton' => 'proton.me',
    ]);
});

test('completes short domain to full domain', function () {
    expect($this->fixer->fix('user@gmail'))->toBe('user@gmail.com');
});

test('completes short domain with non-com TLD', function () {
    expect($this->fixer->fix('user@proton'))->toBe('user@proton.me');
});

test('is a no-op for already-complete domains', function () {
    expect($this->fixer->fix('user@gmail.com'))->toBe('user@gmail.com');
});

test('is a no-op for unknown domains', function () {
    expect($this->fixer->fix('user@unknown'))->toBe('user@unknown');
});

test('returns unchanged if no @ present', function () {
    expect($this->fixer->fix('usergmail'))->toBe('usergmail');
});

test('implements DomainAwareFixer', function () {
    expect($this->fixer)->toBeInstanceOf(DomainAwareFixer::class);
});

test('returns the fixer name', function () {
    expect($this->fixer->name())->toBe('complete_domain');
});
