<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Locale Preset
    |--------------------------------------------------------------------------
    |
    | Activates locale-specific fixers and domain mappings.
    | Set to null to disable locale-specific behavior.
    |
    | Supported: 'it', null
    |
    */
    'locale' => null,

    /*
    |--------------------------------------------------------------------------
    | Domain Map
    |--------------------------------------------------------------------------
    |
    | Maps incomplete domain names to their full domain.
    | Locale presets merge their domains into this map.
    |
    */
    'domains' => [
        'gmail' => 'gmail.com',
        'hotmail' => 'hotmail.com',
        'yahoo' => 'yahoo.com',
        'outlook' => 'outlook.com',
        'icloud' => 'icloud.com',
        'live' => 'live.com',
        'proton' => 'proton.me',
        'protonmail' => 'protonmail.com',
    ],

    /*
    |--------------------------------------------------------------------------
    | Fixers
    |--------------------------------------------------------------------------
    |
    | Ordered list of fixer class names. Set to null for the default pipeline.
    |
    */
    'fixers' => null,

    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    |
    | Configuration for the SanitizeEmails middleware.
    |
    */
    'middleware' => [
        'fields' => ['email', '*_email', 'email_*', '*email*'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Garbage Detection
    |--------------------------------------------------------------------------
    |
    | Thresholds for the isGarbage() quick-reject check.
    |
    */
    'garbage' => [
        'min_length' => 3,
        'require_at' => true,
        'require_dot_in_domain' => true,
    ],

];
