<?php

namespace Neubert\Ncrypt\Tests;

use Neubert\Ncrypt;
use PHPUnit\Framework\TestCase;

class T2SymmetricTest extends TestCase
{
    public function test_symmetric_encryption_5_times()
    {
        for ($i = 0; $i < 5; $i++) {
            $randomText = Ncrypt::passphrase()->withWords(random_int(40, 60));
            $randomPassword = Ncrypt::passphrase()->withAlphaNum()->getLength(random_int(40, 60));

            $chiffre = Ncrypt::symmetric()->withPassword($randomPassword)->encrypt($randomText);

            $compareText = Ncrypt::symmetric()->withPassword($randomPassword)->decrypt($chiffre);
            $this->assertSame($randomText, $compareText);
        }
    }
}
