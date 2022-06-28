# Ncrypt — Secure PHP Encryption Made Simple

### Ncrypt encrypts your data with modern cryptography but is made easy to use, super secure and fast.

<img src="https://img.shields.io/badge/PHP-8.0 / 8.1 / 8.2-blue">

<br>

> ## Foreword
> 
> Ncrypt was developed to support programmers who have no *(real)* knowledge of encryption and don't want to deal with it. On the other hand, since it was developed based on modern and widely accepted encryption concepts, it should potentially appeal to programmers who are familiar with the subject and are glad to use Ncrypt's user-friendly implementation.
> 
> Either way I would encourage every one to review the source code of Ncrypt for their own and in an ideal world everyone would do so at this point. :D



# <span id="doc-0">Table</span> of Contents

- [**A. Missing for 1.0 Release**](#doc-A)
- [**1. Setup**](#doc-1)
  - [**1.1. Installation**](#doc-1-1)
  - [**1.2. Configuration**](#doc-1-2)
    - [**1.2.1. Configuration Parameters**](#doc-1-2-1)
- [**2. Getting Started**](#doc-2)
  - [**2.1. Example for Symmetric Encryption**](#doc-2-1)
  - [**2.2. Example for Asymmetric Encryption**](#doc-2-2)
  - [**2.3. Examples for Passphrase Generation**](#doc-2-3)
- [**3. Implementation Details**](#doc-3)
  - [**3.1. Symmetric Encryption**](#doc-3-1)
    - [**3.1.1. Chiffre Build**](#doc-3-1-1)
  - [**3.2. Asymmetric Encryption**](#doc-3-2)
    - [**3.2.1. Key Generation & Format**](#doc-3-2-1)
    - [**3.2.2. Chiffre Build**](#doc-3-2-2)
  - [**3.3. Passphrase Generation**](#doc-3-3)
    - [**3.3.1. Character Pool Definition**](#doc-3-3-1)
    - [**3.3.2. EFF Wordlist**](#doc-3-3-2)
- [**4. Testing**](#doc-4)
- [**5. Issues & Vulnerabilities**](#doc-5)
- [**6. License**](#doc-6)



# <span id="doc-A">A.</span> Missing for 1.0 Release

- [X] *Passphrase generator*
- [X] *Symmetric encryption*
- [X] *Asymmetric encryption*
- [X] *PHP unit tests*
- [ ] **Documentation**
- [ ] **Config handling via NcryptService**
- [ ] **Better exceptions**
- [ ] **Laravel integration *(service-provider / singleton / facade)***



# <span id="doc-1">1.</span> Setup

Ncrypt is designed from the start to make the setup as easy as possible for beginners as well as senior developers regardless of the knowledge about encryption. Eventhough I would highly reccomend to read through some basics information *(a set of articles and ressources are linked in the [Technical Implementation](#doc-3) part)* to understand the basics.

[**↑ Back to Table of Contents**](#doc-0)


## <span id="doc-1-1">1.1.</span> Installation

For installation Ncrypt can be easily required via [**composer**](https://getcomposer.org/). *A standalone integration without composer is currently not available out of the box.*

Run this command in a shell within your project:

```sh
composer require neubert/ncrypt
```

[**↑ Back to Table of Contents**](#doc-0)



## <span id="doc-1-2">1.2.</span> Configuration

Ncrypt is designed to **run out of the box**. Yet you might find the need to configure some settings your own. Therefore Ncrypt supports two methods for configuration.

#### Configure within Laravel Projects

For Laravel project Ncrypt already comes with .env support out of the box. For cross-deployment configuration Ncrypt also supports publishing the config file like so:

```sh
php artisan publish:ncrypt
```

After this step you should see the Ncrypt configuration within your config directory. (`config/ncrypt.php`)


#### Configure within Other Projects

For projects that aren't based on Laravel the `Neubert\Ncrypt` Facade provides a `config` method to fully customize Ncrypt.

```php
use Neubert\Ncrypt;

Ncrypt::config([ /* your configuration */ ]);
```

[**↑ Back to Table of Contents**](#doc-0)



### <span id="doc-1-2-1">1.2.1.</span> Configuration Parameters

Listed below is the complete default configuration with it's key-value pairs as well as the keys for `.env` files:

```php
return [
    'symmetric' => [
        'key' => [
            'method' => 'pdkdf2',               // Laravel .env: NCRYPT_SYM_KEY_METHOD
            'hash'   => 'sha256',               // Laravel .env: NCRYPT_SYM_KEY_HASH
            'salt'   => 'phpseclib/salt',       // Laravel .env: NCRYPT_SYM_KEY_SALT
            'icount' => 4096,                   // Laravel .env: NCRYPT_SYM_KEY_ICOUNT
        ],
    ],
    'asymmetric' => [
        'key' => [
            'algorithm' => 'id-PBES2',          // Laravel .env: NCRYPT_ASY_KEY_ALGORITHM
            'scheme'    => 'aes256-CBC-PAD',    // Laravel .env: NCRYPT_ASY_KEY_SCHEME
            'prandom'   => 'id-hmacWithSHA512', // Laravel .env: NCRYPT_ASY_KEY_PRANDOM
            'icount'    => 4096,                // Laravel .env: NCRYPT_ASY_KEY_ICOUNT
        ],
    ],
];
```

[**↑ Back to Table of Contents**](#doc-0)



# <span id="doc-2">2.</span> Quick Setup

The useage of Ncrypt is as simple as possible once the configuration is setup.



## <span id="doc-2-1">2.1.</span> Example for Symmetric Encryption

```php
use Neubert\Ncrypt;

$chiffre = Ncrypt::symmetric()->withPassword('demo')->encrypt('ncrypt');
// example result: Tobhb7DtirMX586ocubAsOMiZxdIg2krZA

$content = Ncrypt::symmetric()->withPassword('demo')->decrypt($chiffre);
// expected result: ncrypt
```

*For more information [read the implementation details](#doc-3-1).*



## <span id="doc-2-2">2.2.</span> Example for Asymmetric Encryption

```php
use Neubert\Ncrypt;

// --- key generation ---
// optain a fresh private key
$privateKey = Ncrypt::asymmetric()->createKey();
$publicKey = $privateKey->getPublic();

// --- key output / storage ---
// echo out the private key
echo $privateKey;

// echo out the public key
echo $publicKey;

// --- encryption with the key ---
$chiffre = Ncrypt::asymmetric()->withPublic($publicKey)->encrypt('demo');
// example result: 7h8XrsDgCNJ/NywImSssOBXkdVvz0...

$content = Ncrypt::asymmetric()->withPrivate($privateKey)->decrypt($chiffre);
// expected result: demo
```

*For more information [read the implementation details](#doc-3-2).*



## <span id="doc-2-3">2.3.</span> Examples for Passphrase Generation

```php
use Neubert\Ncrypt;

$passphrase = $ncrypt->passphrase()->getLength(12);
// example result: l<;YMZ`<Qck.

$passphrase = $ncrypt->passphrase()->with('abcdef')->getLength(12);
// example result: bfdefbbbdaea

$passphrase = $ncrypt->passphrase()->with('abcdef')->except('ef')->getLength(12);
// example result: ccaadbccdada

$passphrase = $ncrypt->passphrase()->withLower()->getLength(12); // a-z
// example result: njnemnxnrwct

$passphrase = $ncrypt->passphrase()->withUpper()->getLength(12); // A-Z
// example result: HAMFNECMHFYI

$passphrase = $ncrypt->passphrase()->withLowerNum()->getLength(12); // a-z, 0-9
// example result: dov3atljt1m1

$passphrase = $ncrypt->passphrase()->withUpperNum()->getLength(12); // A-Z, 0-9
// example result: Q97SN9EKSMYT

$passphrase = $ncrypt->passphrase()->withNumbers()->getLength(12); // 0-9
// example result: 072869825629

$passphrase = $ncrypt->passphrase()->withAlpha()->getLength(12); // a-z, A-Z
// example result: SEuyaRXqedvx

$passphrase = $ncrypt->passphrase()->withAlphaNum()->getLength(12); // a-z, A-Z, 0-9
// example result: tJ5RwWJa8kGI

$passphrase = $ncrypt->passphrase()->withReadable()->getLength(12); // A-Z, 0-9 without 01OI
// example result: UMWPMKE67LMF

$passphrase = $ncrypt->passphrase()->withWords(5);
// example result: bullseye-BORAX-THUD-display-algebra
```

*For more information [read the implementation details](#doc-3-3).*



# <span id="doc-3">3.</span> Implementation Details

## <span id="doc-3">3.1.</span> Symmetric Encryption

> _**Note:** For now Ncrypt hasn't implemented an **AES** cipher. If you want read into some reasons why the "**gold standard AES**" isn't a silver bullet I can highly reccomend this awesome article: [**Why AES-GCM Sucks** by Soatok](https://soatok.blog/2020/05/13/why-aes-gcm-sucks/)_

For symmetric encryptions Ncrypt implements an [authenticated encryption](https://en.wikipedia.org/wiki/Authenticated_encryption). It's based uppon the [**phpseclib3 package**](https://phpseclib.com/) for best compability accross systems and implements the `ChaCha20` stream cipher with `Poly 1305` message authentication ([RFC8103](https://datatracker.ietf.org/doc/html/rfc8103), [RFC8439](https://datatracker.ietf.org/doc/html/rfc8439)) for both great security and performance.

Since [encryption keys are required to be 256-bit long](https://datatracker.ietf.org/doc/html/rfc8439#section-2.3), Ncrypt also implements **password-based key derivation via** `PBKDF2` ([RFC2898#section-5.2](https://datatracker.ietf.org/doc/html/rfc2898#section-5.2)). By default Ncrypt uses the `sha256` **hash algorithm** for compatibility reasons yet time-sensitive implementations for bcrypt and/or argon2 are planned for the future. The **iteration count** is set to `4096` by default, which is equivalent to WPA / WPA2 and [RFC2898#section-4.2](https://www.ietf.org/rfc/rfc2898.txt). The **salt for the derivation will automatically be generated** by [phpseclib3](https://phpseclib.com/docs/symmetric#setkey-vs-setpassword).

[**↑ Back to Table of Contents**](#doc-0)


### <span id="doc-3-1-1">3.1.1.</span> Chiffre Build 

For a more streamlined useage Ncrypt automatically serializes the given content via PHP's [**`serialize()`**](https://www.php.net/manual/de/function.serialize.php) function. This will preserve the given content type and return e.g. an `array` after decrypting the given chiffre.

To simplify the handling of the **randomly created 96-bit nonce** ([RFC8439#section-2.3](https://datatracker.ietf.org/doc/html/rfc8439#section-2.3)) Ncyrpt will automatically base64 encode and merge it with the ciphertext created by the encryption since the nonce is not considered to be secret. ([RFC8439#section-2.5](https://datatracker.ietf.org/doc/html/rfc8439#section-2.5))

Additionally the chiffre contains the cipher text without the padding provided by the base64 encoding. (`=` characters at the end of the string)

#### Final Chiffre

The final chiffre contains the randomly created nonce as well as the ciphertext itself. The **first 16 characters** represent the base64 encoded version of the **nonce followed by** the **ciphertext**.

```txt
Example for password "demo" and content "ncrypt"

Tobhb7DtirMX586ocubAsOMiZxdIg2krZA
└──────┬───────┘└───────┬────────┘
  (16) Nonce       Cipher Text
```

[**↑ Back to Table of Contents**](#doc-0)

-----

## <span id="doc-3-2">3.2.</span> Asymmetric Encryption

> _**Note:** Ncrypt doesn't implemented **RSA** and maybe never will. If you want read into some reasons why the well known RSA isn't a great choice nowadays I highly reccomend [**"Seriously, stop using RSA"** by Ben Perez](https://blog.trailofbits.com/2019/07/08/fuck-rsa/)._

For asymmetric encryption Ncrypt implements [**Elliptic Curve Cryptography** *(ECC)*]([#](https://cryptobook.nakov.com/asymmetric-key-ciphers/elliptic-curve-cryptography-ecc)) in a programmer friendly way via the [**Elliptic Curve Integrated Encryption Scheme** *(ECIES)*](https://cryptobook.nakov.com/asymmetric-key-ciphers/ecies-public-key-encryption). The ECC is based on the [**phpseclib3 package**](https://phpseclib.com/) for best compability accross systems. *(e.g. some shared-host businesses still don't offer libsodium)* For the future of Ncrypt a simple ECDH implementation is planned.

The symmetric encryption for the ECIES utilizes [Ncrypts symmetric encryption](#doc-3). *(ChaCha20 & Poly1305)*

[**↑ Back to Table of Contents**](#doc-0)



### <span id="doc-3-2-1">3.2.1.</span> Key Generation & Format

Key generation is based uppon Ed25519 (EdDSA algorithm and Curve25519; [RFC8032](https://datatracker.ietf.org/doc/html/rfc8032)) as it's [the best choice for most common usecases](https://soatok.blog/2022/05/19/guidance-for-choosing-an-elliptic-curve-signature-algorithm-in-2022/).


Ncrypt by default formats private and public keys using the `PKCS8` format [RFC5208](https://datatracker.ietf.org/doc/html/rfc5208). For key encryption the `id-PBES2` algorithm and `aes256-CBC-PAD` scheme is used. The pseudo-random function is set to `id-hmacWithSHA512` with an iteration count of `4096`.

These choices can easily be [altered via configuration](#doc-1-2) if you'd like to change them.

[**↑ Back to Table of Contents**](#doc-0)


### <span id="doc-3-2-2">3.2.2.</span> Chiffre Build

With the calculated secret obtained via the [**Elliptic Curve Diffie–Hellman Key Exchange** *(Elliptic Curve Cryptography by Daniel R. L. Brown – Page 28)*](https://www.secg.org/sec1-v2.pdf) Ncrypt will encrypt the given content [with the symmetric encryption](#doc-3).

The final chiffre includes the public key of the ECDH partner-key (64 characters, appended to the end) as well as the [full symmetric encryption chiffre with nonce and ciphertext](#doc-3-1-1). The chiffre therefor is build up like this:

```txt
Example for content "demo"

7h8XrsDgCNJ/NywImSssOBXkdVvz068MCwwBwYDK2VwBQADIQDAjyI586c0ES8jUpviuoRlTOCy/zLvjp5Fv3mt3UNFqQ==
└──────┬───────┘└──────┬──────┘└──────────────────────────────┬───────────────────────────────┘
  (16) Nonce      Cipher Text                          (64) public key
```

[**↑ Back to Table of Contents**](#doc-0)

-----

## <span id="doc-3-3">3.3.</span> Passphrase Generation

Even though passphrase generation is not fundamentally an encryption task, it is strongly tied to it. Nevertheless, many software libraries leave this part of the implementation entirely up to the developers. To support its users, Ncrypt has implemented a small set of methods for the fully random generation of passphrases, which can be divided into two sections: [passphrases based on character pools](#doc-3-3-1) and [passphrases based on a wordlist](#doc-3-3-2).

[**↑ Back to Table of Contents**](#doc-0)



### <span id="doc-3-3-1">3.3.1.</span> Character Pool

Default passphrases are easy to generate yet the implementation might vary from project to project. In order to make the selection of the desired characters for the passphrase as easy as possible the selection is [implemented via RegEx](https://www.php.net/manual/de/function.preg-match.php).

In the first step Ncrypt will mtach all ASCII characters from `0x20` up to `0x7D` with the given regular expression that is defined by the user. If you aren't familiar with RegEx you can also [use the build-in helper methods](#doc-2-3) for a simple setup.

After the definition a loop will randomly select as many characters as you require from the selected pool of chars. Ncrypt uses the [**`random_int()`**](https://www.php.net/manual/de/function.random-int.php) function provided by PHP which in contrast to **`rand()`** or **`mt_rand()`** is based on `CNG-API` (*Windows*), `getrandom(2)` (*Linux*) or `/dev/urandom` and therefore should generate *"cryptographic random integers"*.

[**↑ Back to Table of Contents**](#doc-0)



### <span id="doc-3-3-2">3.3.2.</span> EFF Wordlist

> If you have never heard of or aren't sure about the security based on wordlist generated passphrases I can highly reccomend [**Exploring the password policy rabbit hole** by Sun Knudsen](https://sunknudsen.com/stories/exploring-the-password-policy-rabbit-hole#1-using-5-word-passphrase-is-less-secure-than-8-character-password) and [**Deep Dive: EFF's New Wordlists for Random Passphrases** by Joseph Bonneau](https://www.eff.org/deeplinks/2016/07/new-wordlists-random-passphrases).

In order to generate easy to read and write passphrases Ncrypt provides passphrases using the [EFF's new wordlist](https://www.eff.org/deeplinks/2016/07/new-wordlists-random-passphrases).

A passphrase created based on a [wordlist can have higher entropy](https://sunknudsen.com/stories/exploring-the-password-policy-rabbit-hole#1-using-5-word-passphrase-is-less-secure-than-8-character-password) than a generic one based on random alphanumeric and special-characters. Ncrypt implements the [EFF large wordlist](https://www.eff.org/files/2016/07/18/eff_large_wordlist.txt) for this which is based on **5 random dice rolls**. For even higher entropy a sixth dice with 2 sides is rolled additionally determining the spelling of the word similar to the ["memorable password" feature of 1Password](https://1password.com/password-generator/). *(`1 = lowercase`, `2 = UPPERCASE`)* This increases the entropy for 5 word passphrases from `64.624...` up to  `69.624...`.

Please note this function requries the word selection to be truly random. Ncrypt uses the [**`random_int()`**](https://www.php.net/manual/de/function.random-int.php) function provided by PHP which in contrast to **`rand()`** or **`mt_rand()`** is based on `CNG-API` (*Windows*), `getrandom(2)` (*Linux*) or `/dev/urandom` and therefore should generate *"cryptographic random integers"*.

[**↑ Back to Table of Contents**](#doc-0)



# <span id="doc-4">4.</span> Testing

Ncrypt offers tests for all implementen functions via phpunit. In order to run phpunit make sure to setup the Ncrypt project with all development dependencies. Ncrypt can be tested on unix systems via makefile:

```sh
make test
```

The test will check for each passphrase generation method and will test the symmetric and asymmetric encryption and decryption for five times in a row.

[**↑ Back to Table of Contents**](#doc-0)



# <span id="doc-5">5.</span> Issues & Vulnerabilities

If you discover a vulnerability or issue with Ncrypt, please [**create a GitHub issue**](https://github.com/danielneubert/ncrypt/issues) to make the problem visible to other _(potential)_ users. If you have any concerns about disclose a vulnerability to the public you can also [send me an e-mail](mailto:ncrypt@danielneubert.com). (*[ncrypt@danielneubert.com](mailto:ncrypt@danielneubert.com)*)

[**↑ Back to Table of Contents**](#doc-0)



# <span id="doc-6">6.</span> License

Ncrypt is an open-sourced software licensed under the MIT license.

[**↑ Back to Table of Contents**](#doc-0)
