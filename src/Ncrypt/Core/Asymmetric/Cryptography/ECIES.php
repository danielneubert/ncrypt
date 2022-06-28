<?php

namespace Neubert\Ncrypt\Core\Asymmetric\Cryptography;

use Neubert\Ncrypt\Core\Asymmetric\PublicKey;
use Neubert\Ncrypt\Interfaces\EncryptionInterface;

/**
 * @method \Neubert\Ncrypt\NcryptService ncrypt()
 * @method \Neubert\Ncrypt\Core\Asymmetric\PrivateKey createKey(?string $password = null)
 * @method self withPrivate(mixed $key)
 * @method self withPublic(mixed $key)
 * @method string getSecret(mixed $private = null, mixed $public = null)
 */
class ECIES extends ECDH implements EncryptionInterface
{
    /**
     * Encrypts the given content with the set public key.
     *
     * @param  mixed  $content
     * @return string
     */
    public function encrypt(mixed $content): string
    {
        $cipherKey = $this->createKey();

        $secretKey = $this->getSecret($cipherKey, $this->publicKey);

        $cipherText = $this->ncrypt()->symmetric()->withPassword($secretKey)->encrypt($content);

        $cipherPublic = $cipherKey->getPublic()->getPEMContent();

        return $cipherText . $cipherPublic;
    }

    /**
     * Decrypts a given chiffre with the set private key.
     *
     * @param  string  $chiffre
     * @return mixed
     */
    public function decrypt(string $chiffre): mixed
    {
        $cipherPublic = substr($chiffre, -64);
        $cipherText = substr($chiffre, 0, -64);

        $cipherPublic = new PublicKey($cipherPublic);

        $secretKey = $this->getSecret($this->privateKey, $cipherPublic);

        return $this->ncrypt()->symmetric()->withPassword($secretKey)->decrypt($cipherText);
    }
}
