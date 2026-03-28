<?php

namespace PlinCode\LaravelEmailFixer\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PlinCode\LaravelEmailFixer\EmailFixer;
use Symfony\Component\HttpFoundation\Response;

class SanitizeEmails
{
    public function handle(Request $request, Closure $next): Response
    {
        $fields = config('email-fixer.middleware.fields', ['email', '*_email', 'email_*', '*email*']);
        $fixer = app(EmailFixer::class);

        $input = $request->all();

        foreach ($input as $key => $value) {
            if (! is_string($value)) {
                continue;
            }

            foreach ($fields as $pattern) {
                if (Str::is($pattern, $key)) {
                    $input[$key] = $fixer->fix($value);
                    break;
                }
            }
        }

        $request->merge($input);

        return $next($request);
    }
}
