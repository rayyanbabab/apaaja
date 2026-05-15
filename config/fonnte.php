<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Fonnte WhatsApp Gateway Configuration
    |--------------------------------------------------------------------------
    |
    | Fonnte is an Indonesian WhatsApp gateway service.
    | Get your token at: https://fonnte.com
    |
    */

    'token' => env('FONNTE_TOKEN', ''),
    'url'   => env('FONNTE_URL', 'https://api.fonnte.com/send'),
];
