# Changelog

All notable changes to `laravel-email-fixer` will be documented in this file.

## v1.0.1 - 2026-11-16

Allow guzzlehttp/promises v3, so the package installs on Laravel 13 with Guzzle 8 (#8).

## First stable release of Laravel Email Fixer - 2026-03-28

A composable pipeline of fixers that automatically repairs malformed email addresses from forms, CSV imports, and mobile
keyboards.

### Features

- **10 built-in fixers**: trim whitespace, strip angle brackets, comma to dot, insert missing @, complete domain, fix
  domain separators, clean local part, clean trailing dots, strip non-ASCII trailing characters, lowercase
- **Italian locale preset**: handles the ò → @ keyboard typo and Italian email providers (libero.it, virgilio.it, etc.)
- **SanitizedEmail validation rule**: auto-fixes and validates in one step, with strict and garbage rejection options
- **SanitizeEmails middleware**: transparently sanitizes all email fields in incoming requests
- **Garbage detection**: quick-reject for inputs that are clearly not emails
- **Diagnostics**: `diagnose()` returns a FixReport with original, fixed value, applied fixers, and validity
- **Batch processing**: `fixMany()` for bulk imports
- **Fully configurable**: custom domain map, fixer pipeline, middleware field patterns, garbage thresholds
- **Standalone usage**: works outside Laravel with `EmailFixer::defaults()`

### Requirements

- PHP 8.3+
- Laravel 12 or 13
