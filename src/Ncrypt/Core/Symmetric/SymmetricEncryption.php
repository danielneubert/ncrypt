<?php

namespace Neubert\Ncrypt\Core\Symmetric;

use Neubert\Ncrypt\Core\CoreInstance;

/**
 * @method \Neubert\Ncrypt\NcryptService ncrypt()
 */
class SymmetricEncryption extends CoreInstance
{
    /**
     * Password set via ->withPassword().
     *
     * @var string
     */
    protected $password;

    /**
     * Sets the password for the upcoming operation.
     *
     * @param  string  $password
     * @return self
     */
    public function withPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    protected function setPassword($cipher)
    {
        return $cipher->setPassword(
            $this->password,
            'pbkdf2',
            'sha256',
            'phpseclib/salt',
            4096,
            $cipher->getKeyLength() >> 3
        );
    }
}
