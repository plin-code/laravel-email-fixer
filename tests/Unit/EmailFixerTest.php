<?php

use PlinCode\LaravelEmailFixer\EmailFixer;
use PlinCode\LaravelEmailFixer\Support\FixReport;

it('runs the fixer pipeline in order', function () {
    $fixer = EmailFixer::defaults(domainMap: ['gmail' => 'gmail.com']);

    expect($fixer->fix('  USER@GMAIL.COM  '))->toBe('user@gmail.com');
});

it('returns empty string for empty input', function () {
    $fixer = EmailFixer::defaults();
    expect($fixer->fix(''))->toBe('');
});

it('does not mangle valid emails', function () {
    $fixer = EmailFixer::defaults(domainMap: ['gmail' => 'gmail.com']);

    $validEmails = [
        'simple@example.com',
        'very.common@example.org',
        'name+tag@example.com',
        'user@subdomain.example.com',
    ];

    foreach ($validEmails as $email) {
        expect($fixer->fix($email))->toBe($email);
    }
});

it('fixes angle brackets and trailing dots', function () {
    $fixer = EmailFixer::defaults(domainMap: ['gmail' => 'gmail.com']);
    expect($fixer->fix('<user@gmail.com.>'))->toBe('user@gmail.com');
});

it('fixes missing @ with known domain', function () {
    $fixer = EmailFixer::defaults(domainMap: ['gmail' => 'gmail.com']);
    expect($fixer->fix('usergmail.com'))->toBe('user@gmail.com');
});

it('completes partial domain', function () {
    $fixer = EmailFixer::defaults(domainMap: ['gmail' => 'gmail.com']);
    expect($fixer->fix('user@gmail'))->toBe('user@gmail.com');
});

it('fixes domain separator', function () {
    $fixer = EmailFixer::defaults(domainMap: ['gmail' => 'gmail.com']);
    expect($fixer->fix('user@gmailcom'))->toBe('user@gmail.com');
});

// fixOrNull
it('returns fixed email when valid', function () {
    $fixer = EmailFixer::defaults(domainMap: ['gmail' => 'gmail.com']);
    expect($fixer->fixOrNull('user@gmail'))->toBe('user@gmail.com');
});

it('returns null when unfixable', function () {
    $fixer = EmailFixer::defaults();
    expect($fixer->fixOrNull('not-an-email'))->toBeNull();
});

// isGarbage
it('detects garbage by minimum length', function () {
    $fixer = EmailFixer::defaults(garbageConfig: ['min_length' => 3, 'require_at' => false, 'require_dot_in_domain' => false]);
    expect($fixer->isGarbage('aa'))->toBeTrue();
    expect($fixer->isGarbage('aaa'))->toBeFalse();
});

it('detects garbage by missing @', function () {
    $fixer = EmailFixer::defaults(garbageConfig: ['min_length' => 1, 'require_at' => true, 'require_dot_in_domain' => false]);
    expect($fixer->isGarbage('notanemail'))->toBeTrue();
    expect($fixer->isGarbage('user@x'))->toBeFalse();
});

it('detects garbage by missing dot in domain', function () {
    $fixer = EmailFixer::defaults(garbageConfig: ['min_length' => 1, 'require_at' => false, 'require_dot_in_domain' => true]);
    expect($fixer->isGarbage('user@localhost'))->toBeTrue();
    expect($fixer->isGarbage('user@gmail.com'))->toBeFalse();
});

it('uses default garbage config', function () {
    $fixer = EmailFixer::defaults();
    expect($fixer->isGarbage('aa'))->toBeTrue();
    expect($fixer->isGarbage('test'))->toBeTrue();
    expect($fixer->isGarbage('user@gmail.com'))->toBeFalse();
});

// diagnose
it('returns a FixReport from diagnose', function () {
    $fixer = EmailFixer::defaults(domainMap: ['gmail' => 'gmail.com']);
    $report = $fixer->diagnose('  USER@GMAIL.COM  ');

    expect($report)->toBeInstanceOf(FixReport::class)
        ->and($report->original)->toBe('  USER@GMAIL.COM  ')
        ->and($report->fixed)->toBe('user@gmail.com')
        ->and($report->wasModified)->toBeTrue()
        ->and($report->appliedFixers)->toContain('trim_whitespace')
        ->and($report->appliedFixers)->toContain('lowercase');
});

it('diagnose reports unmodified for clean emails', function () {
    $fixer = EmailFixer::defaults(domainMap: ['gmail' => 'gmail.com']);
    $report = $fixer->diagnose('user@gmail.com');

    expect($report->wasModified)->toBeFalse()
        ->and($report->appliedFixers)->toBeEmpty();
});

// fixMany
it('batch processes emails via fixMany', function () {
    $fixer = EmailFixer::defaults(domainMap: ['gmail' => 'gmail.com']);
    $results = $fixer->fixMany(['  user@gmail.com  ', 'user@gmail']);

    expect($results)->toHaveCount(2)
        ->and($results[0])->toBeInstanceOf(FixReport::class)
        ->and($results[0]->fixed)->toBe('user@gmail.com')
        ->and($results[1]->fixed)->toBe('user@gmail.com');
});

// custom validator
it('uses custom validator for fixOrNull', function () {
    $fixer = EmailFixer::defaults(
        domainMap: ['gmail' => 'gmail.com'],
        validator: fn (string $email) => $email === 'user@gmail.com',
    );

    expect($fixer->fixOrNull('user@gmail'))->toBe('user@gmail.com');
    expect($fixer->fixOrNull('other@other.com'))->toBeNull();
});
