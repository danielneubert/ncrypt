<?php

namespace Neubert\Ncrypt\Tests;

use Neubert\Ncrypt;
use PHPUnit\Framework\TestCase;

class T3AsymmetricTest extends TestCase
{
    public function test_asymmetric_encryption_5_times()
    {
        for ($i = 0; $i < 5; $i++) {
            $randomText = Ncrypt::passphrase()->withWords(random_int(40, 60));

            $key = Ncrypt::asymmetric()->createKey();

            $chiffre = Ncrypt::asymmetric()->withPublic($key->getPublic())->encrypt($randomText);

            $compareText = Ncrypt::asymmetric()->withPrivate($key)->decrypt($chiffre);
            $this->assertSame($randomText, $compareText);
        }
    }
}
