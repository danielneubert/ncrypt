<?php

namespace Neubert\Ncrypt\Core\Asymmetric;

/**
 * @method \phpseclib3\Crypt\EC\PrivateKey get()
 */
class PrivateKey extends AsymmetricKey
{
    /**
     * Create and load the private key instance.
     *
     * @param mixed $key
     * @param string|null $password
     */
    public function __construct(mixed $key, ?string $password = null)
    {
        $this->load('private', $key, $password);
    }

    /**
     * Return the matching public key instance of the current private key.
     *
     * @return PublicKey
     */
    public function getPublic(): PublicKey
    {
        return new PublicKey($this->get()->getPublicKey());
    }
}
