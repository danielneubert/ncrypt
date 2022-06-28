<?php

namespace Neubert\Ncrypt\Interfaces;

interface EncryptionInterface
{
    /**
     * Serialize and encrypt the given content.
     *
     * @param  mixed  $content
     * @return string
     */
    public function encrypt(mixed $content): string;

    /**
     * Decrypt and unserialize the given content.
     *
     * @param  mixed  $content
     * @return string
     */
    public function decrypt(string $chiffre): mixed;
}
