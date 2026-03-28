<?php

use PlinCode\LaravelEmailFixer\EmailFixer;
use PlinCode\LaravelEmailFixer\Locale\ItalianPreset;
use PlinCode\LaravelEmailFixer\Contracts\LocalePreset;

it('ItalianPreset implements LocalePreset', function () {
    expect(new ItalianPreset)->toBeInstanceOf(LocalePreset::class);
});

it('ItalianPreset provides Italian domains', function () {
    $preset = new ItalianPreset;
    $map = $preset->domainMap();

    expect($map)->toHaveKey('libero', 'libero.it')
        ->and($map)->toHaveKey('virgilio', 'virgilio.it')
        ->and($map)->toHaveKey('live', 'live.it');
});

it('ItalianPreset provides ItalianKeyboard fixer', function () {
    $preset = new ItalianPreset;
    $fixers = $preset->fixers();

    expect($fixers)->toHaveCount(1)
        ->and($fixers[0]->name())->toBe('italian_keyboard');
});

it('fixes Italian keyboard ò with locale', function () {
    $fixer = EmailFixer::defaults(domainMap: ['gmail' => 'gmail.com'])
        ->locale('it');

    expect($fixer->fix('mario.rossiògmailcom'))->toBe('mario.rossi@gmail.com');
});

it('completes Italian domains with locale', function () {
    $fixer = EmailFixer::defaults(domainMap: ['gmail' => 'gmail.com'])
        ->locale('it');

    expect($fixer->fix('info@libero'))->toBe('info@libero.it');
});

it('overrides live to live.it with Italian locale', function () {
    $fixer = EmailFixer::defaults(domainMap: ['live' => 'live.com'])
        ->locale('it');

    expect($fixer->fix('user@live'))->toBe('user@live.it');
});

it('default locale maps live to live.com', function () {
    $fixer = EmailFixer::defaults(domainMap: ['live' => 'live.com']);

    expect($fixer->fix('user@live'))->toBe('user@live.com');
});

it('throws on unknown locale', function () {
    $fixer = EmailFixer::defaults();
    $fixer->locale('xx');
})->throws(InvalidArgumentException::class, 'Unknown locale preset: xx');

it('locale returns a new instance', function () {
    $original = EmailFixer::defaults(domainMap: ['gmail' => 'gmail.com']);
    $italian = $original->locale('it');

    expect($italian)->not->toBe($original);
    // Original should NOT have Italian keyboard
    expect($original->fix('marioògmail.com'))->not->toBe('mario@gmail.com');
});
