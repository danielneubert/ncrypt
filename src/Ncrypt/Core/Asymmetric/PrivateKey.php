<?php

namespace Neubert\Ncrypt\Core\Asymmetric;

use Neubert\Ncrypt\NcryptService;

/**
 * @method \phpseclib3\Crypt\EC\PrivateKey get()
 */
class PrivateKey extends AsymmetricKey
{
    /**
     * Create and load the private key instance.
     *
     * @param NcryptService $ncrypt
     * @param mixed $key
     * @param string|null $password
     */
    public function __construct(NcryptService $ncrypt, mixed $key, ?string $password = null)
    {
        parent::__construct($ncrypt);
        $this->load('private', $key, $password);
    }

    /**
     * Return the matching public key instance of the current private key.
     *
     * @return PublicKey
     */
    public function getPublic(): PublicKey
    {
        return new PublicKey($this->ncrypt(), $this->get()->getPublicKey());
    }
}
