<?php

namespace Neubert\Ncrypt\Core\Asymmetric;

use phpseclib3\Crypt\Common\AsymmetricKey as SecLibAsymmetricKey;
use phpseclib3\Crypt\EC\PrivateKey as SecLibPrivateKey;
use phpseclib3\Crypt\EC\PublicKey as SecLibPublicKey;

class AsymmetricKey implements \JsonSerializable
{
    /**
     * The loaded phpseclib3 key instance.
     *
     * @var \phpseclib3\Crypt\Common\AsymmetricKey
     */
    private $instance;

    /**
     * Tries to create the correct phpseclib3 key instance.
     *
     * @param  string       $type
     * @param  mixed        $key
     * @param  string|null  $password
     * @return void
     */
    protected function load(string $type, mixed $key, ?string $password = null)
    {
        $keyClass = $type == 'private'
            ? SecLibPrivateKey::class
            : SecLibPublicKey::class;

        if (is_object($key) && is_a($key, $keyClass)) {
            $this->instance = $key;
            return;
        } elseif (is_string($key)) {
            $pemSplit = explode('-----', $key);

            if (count($pemSplit) != 5) {
                $key = $this->restorePEM($type, $key);
                $pemSplit = explode('-----', $key);
            }

            if (count($pemSplit) == 5) {
                if (strpos($pemSplit[3], strtoupper($type)) !== false) {
                    $this->instance = $keyClass::load($key, $password ?? false);
                    return;
                }
            }
        }

        throw new \Exception("Unsupported key format.");
    }

    /**
     * Returns the given phpseclib3 key instance.
     *
     * @return \phpseclib3\Crypt\Common\AsymmetricKey
     */
    public function get(): SecLibAsymmetricKey
    {
        return $this->instance;
    }

    /**
     * Returns a compatible PEM formatted key.
     *
     * @return string
     */
    public function getPEM(): string
    {
        $format = [
            'encryptionAlgorithm' => 'id-PBES2',
            'eEncryptionScheme' => 'aes256-CBC-PAD',
            'PRF' => 'id-hmacWithSHA512-256',
            'iterationCount' => 4096,
        ];

        return $this->instance->toString('PKCS8', $format);
    }

    /**
     * Returns just the content of the PEM key without the header / footer.
     *
     * @return string
     */
    public function getPEMContent(): string
    {
        $pem = $this->getPEM();
        $pem = explode('-----', $pem)[2];
        $pem = str_replace(["\n", "\r"], ['', ''], $pem);

        return trim($pem);
    }

    /**
     * Restores the common PEM format from the given key.
     *
     * @param  string  $type
     * @param  string  $key
     * @return string
     */
    private function restorePEM(string $type, string $key): string
    {
        $type = strtoupper($type);
        $size = intval(strlen($key) / 64);

        for ($i = $size; $i > 0; $i--) {
            if (($i * 64) > strlen($key)) {
                continue;
            }

            $key = substr($key, 0, ($i * 64)) . "\r\n" . substr($key, ($i * 64));
        }

        $key = trim($key);

        return "-----BEGIN {$type} KEY-----\r\n{$key}\r\n-----END {$type} KEY-----";
    }

    /**
     * String casts this class to a PEM formatted key.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->getPEM();
    }

    /**
     * Casts this class to a PEM formatted key for debugging.
     *
     * @return array
     */
    public function __debugInfo(): array
    {
        return [
            'PEM' => $this->getPEM(),
        ];
    }

    /**
     * Casts this class to a PEM formatted key for serialization.
     *
     * @return array
     */
    public function __serialize(): array
    {
        return [
            'PEM' => $this->getPEM(),
        ];
    }

    /**
     * Casts this class to a PEM formatted key for json encoding.
     *
     * @return string
     */
    public function jsonSerialize(): string
    {
        return $this->getPEM();
    }
}
