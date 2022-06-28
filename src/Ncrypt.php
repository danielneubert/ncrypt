<?php

namespace Neubert;

use Neubert\Ncrypt\NcryptService;

class Ncrypt
{
    /**
     * Single instance of the Ncrypt service.
     *
     * @var NcryptService
     */
    private static $singleton;

    /**
     * Returns the singleton instance and creates it if neccessary.
     *
     * @return NcryptService
     */
    private static function getSingleton(): NcryptService
    {
        if (!is_a(self::$singleton, NcryptService::class)) {
            self::$singleton = new NcryptService;
        }

        return self::$singleton;
    }

    /**
     * Returns a fresh symmetric encryption instance.
     *
     * @return ChaCha20Poly1305
     */
    public static function symmetric()
    {
        return self::getSingleton()->symmetric();
    }

    /**
     * Returns a fresh asymmetric encryption instance.
     *
     * @return ECIES
     */
    public static function asymmetric()
    {
        return self::getSingleton()->asymmetric();
    }

    /**
     * Returns a fresh passphrase instance.
     *
     * @return Passphrase
     */
    public static function passphrase()
    {
        return self::getSingleton()->passphrase();
    }
}
