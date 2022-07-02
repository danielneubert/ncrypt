<?php

namespace Neubert\Ncrypt;

use Neubert\Ncrypt\Core\Asymmetric\Cryptography\ECIES;
use Neubert\Ncrypt\Core\Generator\Passphrase;
use Neubert\Ncrypt\Core\Symmetric\Cryptography\ChaCha20Poly1305;

class NcryptService
{
    /**
     * Contains the merged config.
     *
     * @var array
     */
    private $configCache;

    /**
     * Create a new instance.
     */
    public function __construct()
    {
        // load defaults ...
        $this->configCache = $this->packagePath('config/ncrypt.php');
        $this->configCache = include $this->configCache;

        // likley a Laravel enviroment
        // merge with the users config
        if (function_exists('config')) {
            $this->configCache = array_merge_recursive($this->configCache, config("ncrypt", []));
        }
    }

    /**
     * Returns a fresh symmetric encryption instance.
     *
     * @return ChaCha20Poly1305
     */
    public function symmetric()
    {
        return new ChaCha20Poly1305($this);
    }

    /**
     * Returns a fresh asymmetric encryption instance.
     *
     * @return ECIES
     */
    public function asymmetric()
    {
        return new ECIES($this);
    }

    /**
     * Returns a fresh passphrase instance.
     *
     * @return Passphrase
     */
    public function passphrase()
    {
        return new Passphrase($this);
    }

    /**
     * Overwrites the given config.
     *
     * @param  array  $config
     * @return NcryptService
     */
    public function config(array $config): NcryptService
    {
        $this->configCache = array_merge_recursive($this->configCache, config("ncrypt", []));
        return $this;
    }

    /**
     * Returns a given config value.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    public function getConfig(string $key, mixed $default = null): mixed
    {
        $key = explode('.', $key);
        $value = count($key) > 0 ? $this->configCache : $default;

        foreach ($key as $section) {
            if (!isset($value[$section])) {
                $value = $default;
                break;
            }

            $value = $value[$section];
        }

        return $value;
    }

    /**
     * Returns the package path.
     *
     * @param  string|null  $append
     * @return string
     */
    public function packagePath(?string $append = null): string
    {
        $append = is_null($append) ? '' : ('/' . ltrim($append, '/'));
        return dirname(dirname(__DIR__)) . str_replace('/', DIRECTORY_SEPARATOR, $append);
    }

    /**
     * Returns the package resource path.
     *
     * @param  string|null  $append
     * @return string
     */
    public function resourcePath(?string $append = null): string
    {
        $append = is_null($append) ? '' : ('/' . ltrim($append, '/'));
        return $this->packagePath('resources' . $append);
    }
}
