<?php

namespace Neubert\Ncrypt\Core\Asymmetric;

class PublicKey extends AsymmetricKey
{
    /**
     * Create and load the public key instance.
     *
     * @param mixed $key
     * @param string|null $password
     */
    public function __construct(mixed $key, ?string $password = null)
    {
        $this->load('public', $key, $password);
    }
}
