<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PlinCode\LaravelEmailFixer\Middleware\SanitizeEmails;

it('sanitizes email field', function () {
    $request = Request::create('/', 'POST', ['email' => '  USER@GMAIL.COM  ']);
    $middleware = new SanitizeEmails;

    $middleware->handle($request, function (Request $req) {
        expect($req->input('email'))->toBe('user@gmail.com');

        return new Response;
    });
});

it('sanitizes fields matching wildcard patterns', function () {
    $request = Request::create('/', 'POST', [
        'contact_email' => '  USER@GMAIL.COM  ',
        'email_backup' => '  OTHER@GMAIL.COM  ',
    ]);
    $middleware = new SanitizeEmails;

    $middleware->handle($request, function (Request $req) {
        expect($req->input('contact_email'))->toBe('user@gmail.com')
            ->and($req->input('email_backup'))->toBe('other@gmail.com');

        return new Response;
    });
});

it('does not modify non-email fields', function () {
    $request = Request::create('/', 'POST', [
        'name' => '  John Doe  ',
        'email' => '  USER@GMAIL.COM  ',
    ]);
    $middleware = new SanitizeEmails;

    $middleware->handle($request, function (Request $req) {
        expect($req->input('name'))->toBe('  John Doe  ')
            ->and($req->input('email'))->toBe('user@gmail.com');

        return new Response;
    });
});

it('skips non-string values', function () {
    $request = Request::create('/', 'POST', [
        'email' => ['array@value.com'],
    ]);
    $middleware = new SanitizeEmails;

    $middleware->handle($request, function (Request $req) {
        expect($req->input('email'))->toBe(['array@value.com']);

        return new Response;
    });
});
