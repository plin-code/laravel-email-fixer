<?php

use PlinCode\LaravelEmailFixer\Contracts\DomainAwareFixer;
use PlinCode\LaravelEmailFixer\Fixers\FixDomainSeparator;

beforeEach(function () {
    $this->fixer = new FixDomainSeparator([
        'gmail' => 'gmail.com',
        'hotmail' => 'hotmail.com',
    ]);
});

test('fixes missing dot in domain', function () {
    expect($this->fixer->fix('user@gmailcom'))->toBe('user@gmail.com');
});

test('fixes hyphen used instead of dot', function () {
    expect($this->fixer->fix('user@gmail-com'))->toBe('user@gmail.com');
});

test('is a no-op for valid domains', function () {
    expect($this->fixer->fix('user@gmail.com'))->toBe('user@gmail.com');
});

test('is a no-op for unknown domains', function () {
    expect($this->fixer->fix('user@unknown-org'))->toBe('user@unknown-org');
});

test('returns unchanged if no @ present', function () {
    expect($this->fixer->fix('usergmailcom'))->toBe('usergmailcom');
});

test('implements DomainAwareFixer', function () {
    expect($this->fixer)->toBeInstanceOf(DomainAwareFixer::class);
});

test('returns the fixer name', function () {
    expect($this->fixer->name())->toBe('fix_domain_separator');
});
