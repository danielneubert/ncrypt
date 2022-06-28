<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Symmetric Encryption
    |--------------------------------------------------------------------------
    |
    | ...
    |
    */
    'symmetric' => [
        'key' => [
            'method' => env('NCRYPT_SYM_KEY_METHOD', 'pdkdf2'),
            'hash'   => env('NCRYPT_SYM_KEY_HASH', 'sha256'),
            'salt'   => env('NCRYPT_SYM_KEY_SALT', 'phpseclib/salt'),
            'icount' => env('NCRYPT_SYM_KEY_ICOUNT', 4096),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Asymmetric Encryption
    |--------------------------------------------------------------------------
    |
    | ...
    |
    */
    'asymmetric' => [
        'key' => [
            'algorithm' => env('NCRYPT_ASY_KEY_ALGORITHM', 'id-PBES2'),
            'scheme'    => env('NCRYPT_ASY_KEY_SCHEME', 'aes256-CBC-PAD'),
            'prandom'   => env('NCRYPT_ASY_KEY_PRANDOM', 'id-hmacWithSHA512'),
            'icount'    => env('NCRYPT_ASY_KEY_ICOUNT', 4096),
        ],
    ],
];
