<?php

use PlinCode\LaravelEmailFixer\Support\FixReport;

it('stores all properties as readonly', function () {
    $report = new FixReport(
        original: 'test ò gmail',
        fixed: 'test@gmail.com',
        appliedFixers: ['italian_keyboard', 'complete_domain'],
        isValid: true,
        isGarbage: false,
        wasModified: true,
    );

    expect($report->original)->toBe('test ò gmail')
        ->and($report->fixed)->toBe('test@gmail.com')
        ->and($report->appliedFixers)->toBe(['italian_keyboard', 'complete_domain'])
        ->and($report->isValid)->toBeTrue()
        ->and($report->isGarbage)->toBeFalse()
        ->and($report->wasModified)->toBeTrue();
});

it('reports unmodified email', function () {
    $report = new FixReport(
        original: 'valid@email.com',
        fixed: 'valid@email.com',
        appliedFixers: [],
        isValid: true,
        isGarbage: false,
        wasModified: false,
    );

    expect($report->wasModified)->toBeFalse()
        ->and($report->appliedFixers)->toBeEmpty();
});
