<?php

namespace Neubert\Ncrypt\Core\Asymmetric\Cryptography;

use Neubert\Ncrypt\Core\Asymmetric\AsymmetricEncryption;
use Neubert\Ncrypt\Core\Asymmetric\PrivateKey;
use phpseclib3\Crypt\EC as SecLibEC;

/**
 * @method \Neubert\Ncrypt\NcryptService ncrypt()
 * @method self withPrivate(mixed $key)
 * @method self withPublic(mixed $key)
 */
class EC extends AsymmetricEncryption
{
    /**
     * Create a keypair and get the private key.
     *
     * @param  string|null  $password
     * @return PrivateKey
     */
    public function createKey(?string $password = null): PrivateKey
    {
        $key = SecLibEC::createKey('Ed25519');

        if ($password !== null) {
            $key->withPassword($password);
        }

        return new PrivateKey($this->ncrypt(), $key);
    }
}
