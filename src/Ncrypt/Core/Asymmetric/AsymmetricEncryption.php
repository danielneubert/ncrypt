<?php

namespace Neubert\Ncrypt\Core\Asymmetric;

use Neubert\Ncrypt\Core\Asymmetric\PrivateKey;
use Neubert\Ncrypt\Core\Asymmetric\PublicKey;
use Neubert\Ncrypt\Core\CoreInstance;

/**
 * @method \Neubert\Ncrypt\NcryptService ncrypt()
 */
class AsymmetricEncryption extends CoreInstance
{
    /**
     * Private key set via ->withPrivate().
     *
     * @var \Neubert\Ncrypt\Core\Asymmetric\PrivateKey
     */
    protected $privateKey;

    /**
     * Public key set via ->withPublic().
     *
     * @var \Neubert\Ncrypt\Core\Asymmetric\PublicKey
     */
    protected $publicKey;

    /**
     * Sets the private key for the upcoming operation.
     *
     * @param  string|PrivateKey  $key
     * @return self
     */
    public function withPrivate(mixed $key): self
    {
        $this->privateKey = is_a($key, PrivateKey::class) ? $key : new PrivateKey($this->ncrypt(), $key);
        return $this;
    }

    /**
     * Sets the public key for the upcoming operation.
     *
     * @param  string|PublicKey  $key
     * @return self
     */
    public function withPublic(mixed $key): self
    {
        $this->publicKey = is_a($key, PublicKey::class) ? $key : new PublicKey($this->ncrypt(), $key);
        return $this;
    }
}
