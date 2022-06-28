<?php

namespace Neubert\Ncrypt\Tests;

use Neubert\Ncrypt;
use PHPUnit\Framework\TestCase;

class PassphraseTest extends TestCase
{
    private $chars = [
        'lower' => ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'],
        'upper' => ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'],
        'numeric' => ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
    ];

    public function test_passphrases_can_be_created()
    {
        $passphrase = Ncrypt::passphrase()->getLength(12);
        $this->assertEquals(12, strlen($passphrase));
    }

    public function test_passphrases_basic_with_method()
    {
        $passphrase = Ncrypt::passphrase()->with('abc')->getLength(128);

        $compare = str_replace(['a', 'b', 'c'], '', $passphrase);

        $this->assertEquals($compare, '');
    }

    public function test_passphrases_basic_except_method()
    {
        $passphrase = Ncrypt::passphrase()->with('abc')->except('a')->getLength(128);

        $compare = str_replace(['b', 'c'], '', $passphrase);

        $this->assertEquals($compare, '');
    }

    public function test_passphrases_with_lower_helper_method()
    {
        $passphrase = Ncrypt::passphrase()->withLower()->getLength(128);

        $compare = str_replace($this->chars['lower'], '', $passphrase);

        $this->assertEquals($compare, '');
    }

    public function test_passphrases_with_upper_helper_method()
    {
        $passphrase = Ncrypt::passphrase()->withUpper()->getLength(128);

        $compare = str_replace($this->chars['upper'], '', $passphrase);

        $this->assertEquals($compare, '');
    }

    public function test_passphrases_with_lower_num_helper_method()
    {
        $passphrase = Ncrypt::passphrase()->withLowerNum()->getLength(128);

        $compare = str_replace([...$this->chars['lower'], ...$this->chars['numeric']], '', $passphrase);

        $this->assertEquals($compare, '');
    }

    public function test_passphrases_with_upper_num_helper_method()
    {
        $passphrase = Ncrypt::passphrase()->withUpperNum()->getLength(128);

        $compare = str_replace([...$this->chars['upper'], ...$this->chars['numeric']], '', $passphrase);

        $this->assertEquals($compare, '');
    }

    public function test_passphrases_with_numbers_helper_method()
    {
        $passphrase = Ncrypt::passphrase()->withNumbers()->getLength(128);

        $compare = str_replace($this->chars['numeric'], '', $passphrase);

        $this->assertEquals($compare, '');
    }

    public function test_passphrases_with_alpha_helper_method()
    {
        $passphrase = Ncrypt::passphrase()->withAlpha()->getLength(128);

        $compare = str_replace([...$this->chars['lower'], ...$this->chars['upper']], '', $passphrase);

        $this->assertEquals($compare, '');
    }

    public function test_passphrases_with_alpha_num_helper_method()
    {
        $passphrase = Ncrypt::passphrase()->withAlphaNum()->getLength(128);

        $compare = str_replace([...$this->chars['lower'], ...$this->chars['upper'], ...$this->chars['numeric']], '', $passphrase);

        $this->assertEquals($compare, '');
    }

    public function test_passphrases_with_words_helper_method()
    {
        $passphrase = Ncrypt::passphrase()->withWords(64);
        $passphrase = explode('-', $passphrase);

        $wordlist = dirname(__DIR__) . '/resources/eff-large-wordlist.php';
        $wordlist = include $wordlist;

        $missing = false;

        foreach ($passphrase as $word) {
            if (!in_array(strtolower($word), $wordlist)) {
                $missing = true;
                break;
            }
        }

        $this->assertFalse($missing);
    }
}
