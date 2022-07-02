<?php

namespace Neubert\Ncrypt\Core\Asymmetric;

use Neubert\Ncrypt\NcryptService;

class PublicKey extends AsymmetricKey
{
    /**
     * Create and load the public key instance.
     *
     * @param NcryptService $ncrypt
     * @param mixed $key
     * @param string|null $password
     */
    public function __construct(NcryptService $ncrypt, mixed $key, ?string $password = null)
    {
        parent::__construct($ncrypt);
        $this->load('public', $key, $password);
    }
}
