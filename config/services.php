<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'facebook' => [
        'client_id' => '450936228628144',
        'client_secret' => '95c8a53300b3cb592ea374c5a1a0efab',
        'redirect' => 'http://amarillas365.com/callback/facebook',
    ],
    'twitter' => [
        'client_id' => 'tRJqkWxVOI4jTydX7Gg7VHaCn',
        'client_secret' => 'oGRgoQ9HlsKRP9JaeFtjNlvy34XQOQy9vpQJtVe4qfBVBNhWLN',
        'redirect' => 'http://amarillas365.com/callback/twitter',
    ],
    'google' => [
        'client_id' => '516847296512-9brl3kjp39v23tvqc9iurmmtl793v1le.apps.googleusercontent.com',
        'client_secret' => '7v4Oizve2IywloPMg6LXZGXM',
        'redirect' => 'http://amarillas365.com/callback/google',
    ],

];
