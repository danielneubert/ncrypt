<?php

namespace Neubert\Ncrypt\Core\Symmetric\Cryptography;

use Neubert\Ncrypt\Core\Symmetric\SymmetricEncryption;
use Neubert\Ncrypt\Interfaces\EncryptionInterface;
use phpseclib3\Crypt\ChaCha20;

/**
 * @method \Neubert\Ncrypt\NcryptService ncrypt()
 * @method self withPassword(string $password)
 * @method void setPassword($cipher)
 */
class ChaCha20Poly1305 extends SymmetricEncryption implements EncryptionInterface
{
    public function encrypt(mixed $content): string
    {
        $nonce = random_bytes(12);
        $cipher = $this->setup($nonce);

        $ciphertext = $cipher->encrypt(serialize($content));

        return base64_encode($nonce) . rtrim(base64_encode($ciphertext), '=');
    }

    public function decrypt(string $chiffre): mixed
    {
        $nonce = base64_decode(substr($chiffre, 0, 16));
        $ciphertext = base64_decode(substr($chiffre, 16));

        $cipher = $this->setup($nonce);

        return unserialize($cipher->decrypt($ciphertext));
    }

    private function setup(string $nonce)
    {
        $cipher = new ChaCha20;
        $cipher->enablePoly1305();
        $cipher->setNonce($nonce);
        $this->setPassword($cipher);
        return $cipher;
    }
}
