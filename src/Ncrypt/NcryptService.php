<?php

namespace Neubert\Ncrypt;

use Neubert\Ncrypt\Core\Asymmetric\Cryptography\ECIES;
use Neubert\Ncrypt\Core\Generator\Passphrase;
use Neubert\Ncrypt\Core\Symmetric\Cryptography\ChaCha20Poly1305;

class NcryptService
{
    /**
     * Returns a fresh symmetric encryption instance.
     *
     * @return ChaCha20Poly1305
     */
    public function symmetric()
    {
        return new ChaCha20Poly1305($this);
    }

    /**
     * Returns a fresh asymmetric encryption instance.
     *
     * @return ECIES
     */
    public function asymmetric()
    {
        return new ECIES($this);
    }

    /**
     * Returns a fresh passphrase instance.
     *
     * @return Passphrase
     */
    public function passphrase()
    {
        return new Passphrase($this);
    }

    /**
     * Returns the package path.
     *
     * @param  string|null  $append
     * @return string
     */
    public function packagePath(?string $append = null): string
    {
        $append = is_null($append) ? '' : ('/' . ltrim($append, '/'));
        return dirname(dirname(__DIR__)) . str_replace('/', DIRECTORY_SEPARATOR, $append);
    }

    /**
     * Returns the package resource path.
     *
     * @param  string|null  $append
     * @return string
     */
    public function resourcePath(?string $append = null): string
    {
        $append = is_null($append) ? '' : ('/' . ltrim($append, '/'));
        return $this->packagePath('resources' . $append);
    }
}
