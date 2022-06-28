<?php

namespace Neubert\Ncrypt\Core\Asymmetric\Cryptography;

use phpseclib3\Crypt\DH as SecLibDC;

/**
 * @method \Neubert\Ncrypt\NcryptService ncrypt()
 * @method \Neubert\Ncrypt\Core\Asymmetric\PrivateKey createKey(?string $password = null)
 * @method self withPrivate(mixed $key)
 * @method self withPublic(mixed $key)
 */
class ECDH extends EC
{
    /**
     * Creates the Diffie–Hellman key exchange secret for the given key-pair.
     *
     * @param  mixed  $private
     * @param  mixed  $public
     * @return string
     */
    public function getSecret(mixed $private = null, mixed $public = null): string
    {
        $private = $private ?? $this->privateKey;
        $public = $public ?? $this->publicKey;

        return SecLibDC::computeSecret($private->get(), $public->get());
    }
}
