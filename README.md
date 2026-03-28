# Laravel Email Fixer

[![Latest Version on Packagist](https://img.shields.io/packagist/v/plin-code/laravel-email-fixer.svg?style=flat-square)](https://packagist.org/packages/plin-code/laravel-email-fixer)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/plin-code/laravel-email-fixer/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/plin-code/laravel-email-fixer/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/plin-code/laravel-email-fixer/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/plin-code/laravel-email-fixer/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/plin-code/laravel-email-fixer.svg?style=flat-square)](https://packagist.org/packages/plin-code/laravel-email-fixer)

Sanitize, normalize and auto-correct malformed email addresses in Laravel. Handles common typos from web forms, CSV imports, and mobile keyboards, including locale-specific issues like the Italian `ò` → `@` keyboard quirk.

## The Problem

Users constantly submit broken email addresses. Typos, missing `@` signs, incomplete domains, trailing dots, angle brackets from copy-paste, commas instead of dots. Every registration form, every CSV import, every contact form collects these. Most apps just reject them and lose the user.

**Laravel Email Fixer** automatically repairs these emails before validation, so your users don't bounce off your forms.

### What It Fixes

| Input | Output | Fixer |
|-------|--------|-------|
| `  user@gmail.com  ` | `user@gmail.com` | TrimWhitespace |
| `<user@gmail.com>` | `user@gmail.com` | StripAngleBrackets |
| `user@gmail,com` | `user@gmail.com` | CommaToDot |
| `usergmail.com` | `user@gmail.com` | InsertMissingAt |
| `user@gmail` | `user@gmail.com` | CompleteDomain |
| `user@gmailcom` | `user@gmail.com` | FixDomainSeparator |
| `user.@gmail.com` | `user@gmail.com` | CleanLocalPart |
| `user@gmail.com.` | `user@gmail.com` | CleanTrailingDots |
| `User@Gmail.COM` | `user@gmail.com` | Lowercase |
| `user@gmail.com§` | `user@gmail.com` | StripNonAsciiTrailing |
| `userògmail.com` | `user@gmail.com` | ItalianKeyboard (locale: `it`) |

## Installation

```bash
composer require plin-code/laravel-email-fixer
```

Optionally publish the config file:

```bash
php artisan vendor:publish --tag="laravel-email-fixer-config"
```

## Quick Start

### Using the Facade

```php
use PlinCode\LaravelEmailFixer\Facades\EmailFixer;

// Fix a single email
$fixed = EmailFixer::fix('user@gmail,com');
// "user@gmail.com"

// Fix or get null if unfixable
$fixed = EmailFixer::fixOrNull('not-an-email');
// null

// Check if input is garbage before attempting a fix
$isGarbage = EmailFixer::isGarbage('asdf');
// true

// Get a detailed report
$report = EmailFixer::diagnose('usergmail.com');
// $report->original       → "usergmail.com"
// $report->fixed          → "user@gmail.com"
// $report->appliedFixers  → ["InsertMissingAt"]
// $report->isValid        → true
// $report->wasModified    → true

// Batch processing
$reports = EmailFixer::fixMany([
    'user@gmail,com',
    'admin@yahoo',
    'info@hotmail.com.',
]);
```

### Using the Validation Rule

Apply the `SanitizedEmail` rule to auto-fix and validate email fields in one step. The fixed value is automatically merged back into the request.

```php
use PlinCode\LaravelEmailFixer\Rules\SanitizedEmail;

public function rules(): array
{
    return [
        'email' => ['required', new SanitizedEmail],
    ];
}
```

With options:

```php
// Enable strict RFC validation and garbage rejection
new SanitizedEmail(strict: true, rejectGarbage: true)

// With Italian locale
new SanitizedEmail(locale: 'it')
```

### Using the Middleware

Register the `SanitizeEmails` middleware to automatically fix all email fields in incoming requests before they reach your controllers.

```php
use PlinCode\LaravelEmailFixer\Middleware\SanitizeEmails;

// In a route group
Route::middleware(SanitizeEmails::class)->group(function () {
    Route::post('/register', [RegisterController::class, 'store']);
});

// Or globally in bootstrap/app.php
->withMiddleware(function (Middleware $middleware) {
    $middleware->append(SanitizeEmails::class);
})
```

By default, the middleware targets fields matching these patterns: `email`, `*_email`, `email_*`, `*email*`. You can customize this in the config file.

## Use Cases

### Registration and Login Forms

The most common scenario. Users mistype their email on signup, never receive the confirmation, and leave. With Email Fixer, most typos are silently corrected.

```php
// In your registration form request
public function rules(): array
{
    return [
        'email' => ['required', new SanitizedEmail(rejectGarbage: true), 'unique:users'],
    ];
}
```

### CSV/Bulk Import

When importing contacts or users from spreadsheets, email quality is often poor. Use `fixMany()` to clean them in bulk and `diagnose()` to flag the ones that could not be repaired.

```php
use PlinCode\LaravelEmailFixer\Facades\EmailFixer;

$emails = array_column($rows, 'email');

$reports = EmailFixer::fixMany($emails);

foreach ($reports as $index => $report) {
    if ($report->isValid) {
        $rows[$index]['email'] = $report->fixed;
    } else {
        $failed[] = $rows[$index]; // flag for manual review
    }
}
```

### Italian (or Locale-Specific) Users

Italian keyboards place the `ò` key right next to the `@` key, causing a very common typo. Enable the Italian locale to handle this automatically, along with local domain shortcuts like `libero` → `libero.it`.

```php
// Via config (config/email-fixer.php)
'locale' => 'it',

// Or at runtime
$fixer = EmailFixer::locale('it');
$fixer->fix('mrossiòlibero'); // "mrossi@libero.it"
```

### API Input Sanitization

Use the middleware on your API routes to transparently sanitize emails before any validation or processing takes place.

```php
Route::middleware(SanitizeEmails::class)
    ->prefix('api')
    ->group(function () {
        Route::post('/subscribe', [NewsletterController::class, 'subscribe']);
        Route::post('/invite', [InviteController::class, 'send']);
    });
```

### Auditing and Debugging

Use `diagnose()` to understand exactly what was changed and why, useful for logging or admin dashboards.

```php
$report = EmailFixer::diagnose('  USER@Gmail,COM.  ');

logger()->info('Email fixed', [
    'original' => $report->original,
    'fixed' => $report->fixed,
    'fixers' => $report->appliedFixers,
    'was_modified' => $report->wasModified,
]);
```

## Configuration

The published config file (`config/email-fixer.php`) allows you to customize:

```php
return [
    // Locale preset: 'it' or null
    'locale' => null,

    // Domain shortcuts (expanded by CompleteDomain fixer)
    'domains' => [
        'gmail'       => 'gmail.com',
        'hotmail'     => 'hotmail.com',
        'yahoo'       => 'yahoo.com',
        'outlook'     => 'outlook.com',
        'icloud'      => 'icloud.com',
        'live'        => 'live.com',
        'proton'      => 'proton.me',
        'protonmail'  => 'protonmail.com',
    ],

    // Custom fixer pipeline (null = default pipeline)
    'fixers' => null,

    // Middleware field patterns
    'middleware' => [
        'fields' => ['email', '*_email', 'email_*', '*email*'],
    ],

    // Garbage detection thresholds
    'garbage' => [
        'min_length' => 3,
        'require_at' => true,
        'require_dot_in_domain' => true,
    ],
];
```

### Custom Fixer Pipeline

You can define your own fixer order or add custom fixers:

```php
'fixers' => [
    \PlinCode\LaravelEmailFixer\Fixers\TrimWhitespace::class,
    \PlinCode\LaravelEmailFixer\Fixers\Lowercase::class,
    \App\EmailFixers\MyCustomFixer::class,
],
```

Custom fixers must implement `PlinCode\LaravelEmailFixer\Contracts\FixerInterface`:

```php
use PlinCode\LaravelEmailFixer\Contracts\FixerInterface;

class MyCustomFixer implements FixerInterface
{
    public function fix(string $email): string
    {
        // your logic here
        return $email;
    }

    public function name(): string
    {
        return 'MyCustomFixer';
    }
}
```

## Standalone Usage

You can use Email Fixer outside of Laravel:

```php
use PlinCode\LaravelEmailFixer\EmailFixer;

$fixer = EmailFixer::defaults(
    domainMap: ['gmail' => 'gmail.com', 'yahoo' => 'yahoo.com'],
);

$fixed = $fixer->fix('user@gmail');
// "user@gmail.com"
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Daniele Barbaro](https://github.com/plin-code)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
