<?php

use PlinCode\LaravelEmailFixer\Contracts\DomainAwareFixer;
use PlinCode\LaravelEmailFixer\Fixers\InsertMissingAt;

beforeEach(function () {
    $this->fixer = new InsertMissingAt([
        'gmail' => 'gmail.com',
        'hotmail' => 'hotmail.com',
        'yahoo' => 'yahoo.com',
    ]);
});

test('inserts @ before full known domain', function () {
    expect($this->fixer->fix('usergmail.com'))->toBe('user@gmail.com');
});

test('inserts @ before short known domain key', function () {
    expect($this->fixer->fix('usergmail'))->toBe('user@gmail.com');
});

test('is a no-op if @ already present', function () {
    expect($this->fixer->fix('user@gmail.com'))->toBe('user@gmail.com');
});

test('is a no-op for unknown domains', function () {
    expect($this->fixer->fix('userunknown.org'))->toBe('userunknown.org');
});

test('does not insert @ if local part would be empty', function () {
    expect($this->fixer->fix('gmail.com'))->toBe('gmail.com');
});

test('matches longest domain first', function () {
    $fixer = new InsertMissingAt([
        'hotmail' => 'hotmail.com',
        'mail' => 'mail.com',
    ]);

    expect($fixer->fix('userhotmail.com'))->toBe('user@hotmail.com');
});

test('implements DomainAwareFixer', function () {
    expect($this->fixer)->toBeInstanceOf(DomainAwareFixer::class);
});

test('returns the fixer name', function () {
    expect($this->fixer->name())->toBe('insert_missing_at');
});
