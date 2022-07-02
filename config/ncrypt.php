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
            'method' => function_exists('env') ? env('NCRYPT_SYM_KEY_METHOD', 'pbkdf2') : 'pbkdf2',
            'hash'   => function_exists('env') ? env('NCRYPT_SYM_KEY_HASH', 'sha256') : 'sha256',
            'salt'   => function_exists('env') ? env('NCRYPT_SYM_KEY_SALT', 'phpseclib/salt') : 'phpseclib/salt',
            'icount' => function_exists('env') ? env('NCRYPT_SYM_KEY_ICOUNT', 4096) : 4096,
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
            'algorithm' => function_exists('env') ? env('NCRYPT_ASY_KEY_ALGORITHM', 'id-PBES2') : 'id-PBES2',
            'scheme'    => function_exists('env') ? env('NCRYPT_ASY_KEY_SCHEME', 'aes256-CBC-PAD') : 'aes256-CBC-PAD',
            'prandom'   => function_exists('env') ? env('NCRYPT_ASY_KEY_PRANDOM', 'id-hmacWithSHA512') : 'id-hmacWithSHA512',
            'icount'    => function_exists('env') ? env('NCRYPT_ASY_KEY_ICOUNT', 4096) : 4096,
        ],
    ],
];
