<?php

arch('it will not use debugging functions')
    ->expect(['dd', 'dump', 'ray'])
    ->each->not->toBeUsed();

arch('core classes do not depend on Laravel')
    ->expect('PlinCode\LaravelEmailFixer\Contracts')
    ->not->toUse('Illuminate');

arch('fixers do not depend on Laravel')
    ->expect('PlinCode\LaravelEmailFixer\Fixers')
    ->not->toUse('Illuminate');

arch('support classes do not depend on Laravel')
    ->expect('PlinCode\LaravelEmailFixer\Support')
    ->not->toUse('Illuminate');

arch('locale presets do not depend on Laravel')
    ->expect('PlinCode\LaravelEmailFixer\Locale')
    ->not->toUse('Illuminate');

arch('EmailFixer core does not depend on Laravel')
    ->expect('PlinCode\LaravelEmailFixer\EmailFixer')
    ->not->toUse('Illuminate');

arch('all fixers implement FixerInterface')
    ->expect('PlinCode\LaravelEmailFixer\Fixers')
    ->toImplement('PlinCode\LaravelEmailFixer\Contracts\FixerInterface');
