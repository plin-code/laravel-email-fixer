<?php

use Illuminate\Support\Facades\Validator;
use PlinCode\LaravelEmailFixer\Rules\SanitizedEmail;

it('passes for valid email', function () {
    $validator = Validator::make(
        ['email' => 'user@gmail.com'],
        ['email' => ['required', new SanitizedEmail]],
    );

    expect($validator->passes())->toBeTrue();
});

it('fixes and passes for fixable email', function () {
    $data = ['email' => '  USER@GMAIL.COM  '];
    $validator = Validator::make(
        $data,
        ['email' => ['required', new SanitizedEmail]],
    );

    expect($validator->passes())->toBeTrue();
});

it('fails for unfixable email', function () {
    $validator = Validator::make(
        ['email' => 'not-an-email'],
        ['email' => ['required', new SanitizedEmail]],
    );

    expect($validator->fails())->toBeTrue();
});

it('rejects garbage when rejectGarbage is true', function () {
    $validator = Validator::make(
        ['email' => 'aa'],
        ['email' => ['required', new SanitizedEmail(rejectGarbage: true)]],
    );

    expect($validator->fails())->toBeTrue();
});

it('applies locale when specified', function () {
    $validator = Validator::make(
        ['email' => 'marioògmail.com'],
        ['email' => ['required', new SanitizedEmail(locale: 'it')]],
    );

    expect($validator->passes())->toBeTrue();
});

it('mutates the request value', function () {
    request()->merge(['email' => '  USER@GMAIL.COM  ']);

    $validator = Validator::make(
        request()->all(),
        ['email' => ['required', new SanitizedEmail]],
    );

    $validator->passes();

    expect(request()->input('email'))->toBe('user@gmail.com');
});
