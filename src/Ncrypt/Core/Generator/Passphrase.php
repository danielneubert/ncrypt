<?php

namespace Neubert\Ncrypt\Core\Generator;

use Neubert\Ncrypt\Core\CoreInstance;

/**
 * @method \Neubert\Ncrypt\NcryptService ncrypt()
 */
class Passphrase extends CoreInstance
{
    /**
     * The regular expression ruleset split into `match` and negative lookahead *(`negative_lah`)*.
     *
     * @var array
     */
    private $ruleset = [
        'match' => '',
        'negative_lah' => '',
    ];

    /**
     * Set a ruleset for matching characters.
     *
     * @param  string  $regex
     * @return Passphrase
     */
    public function with(string $regex): self
    {
        $this->ruleset['match'] .= str_replace([
            '\\',
            '/',
            '-',
            ',',
        ], [
            '\\\\',
            '\/',
            '\-',
            '\,',
        ], $regex);
        return $this;
    }

    /**
     * Set a ruleset for characters to explicitly exclude.
     *
     * @param  string  $regex
     * @return Passphrase
     */
    public function except(string $regex): self
    {
        $this->ruleset['negative_lah'] .= str_replace([
            '\\',
            '/',
            '-',
            ',',
        ], [
            '\\\\',
            '\/',
            '\-',
            '\,',
        ], $regex);
        return $this;
    }

    /**
     * Allow the predefined preset of readable characters for something like a licence.
     *
     * Includes: A-Z and 0-9 without 1, 0, I and O
     *
     * @return self
     */
    public function withReadable(): self
    {
        return $this->with('[:upper:][:digit:]')->except('01IO');
    }

    /**
     * Allow the lowercase characters.
     *
     * Match: a-z
     *
     * @return self
     */
    public function withLower(): self
    {
        return $this->with('[:lower:]');
    }

    /**
     * Allow the uppercase characters.
     *
     * Match: A-Z
     *
     * @return self
     */
    public function withUpper(): self
    {
        return $this->with('[:upper:]');
    }

    /**
     * Allow the lowercase characters and numerics.
     *
     * Match: a-z, 0-9
     *
     * @return self
     */
    public function withLowerNum(): self
    {
        return $this->with('[:lower:][:digit:]');
    }

    /**
     * Allow the uppercase characters and numerics.
     *
     * Match: A-Z, 0-9
     *
     * @return self
     */
    public function withUpperNum(): self
    {
        return $this->with('[:upper:][:digit:]');
    }

    /**
     * Allow all numerics.
     *
     * Match: 0-9
     *
     * @return self
     */
    public function withNumbers(): self
    {
        return $this->with('[:digit:]');
    }

    /**
     * Allow all standard letters.
     *
     * Match: a-z, A-Z
     *
     * @return self
     */
    public function withAlpha(): self
    {
        return $this->with('[:alpha:]');
    }


    /**
     * Allow all standard letters and numerics.
     *
     * Match: a-z, A-Z, 0-9
     *
     * @return self
     */
    public function withAlphaNum(): self
    {
        return $this->with('[:alnum:]');
    }

    /**
     * Return the resulting character list based in the defined regular expression.
     *
     * @return string
     */
    private function getCharacterList(): string
    {
        $characters = '';
        $regex = $this->ruleset['negative_lah'] != '' ? "(?![{$this->ruleset['negative_lah']}])" : '';
        $regex = $this->ruleset['match'] != '' ? "{$regex}[{$this->ruleset['match']}]" : $regex;
        $regex = "/{$regex}/";

        for ($pos = 32; $pos < 126; $pos++) {
            if (preg_match($regex, chr($pos))) {
                $characters .= chr($pos);
            }
        }

        return $characters;
    }

    /**
     * Get the selected passphrase by a set character length.
     *
     * @param  integer  $length
     * @return void
     */
    public function getLength(int $length)
    {
        $characters = $this->getCharacterList();
        $characterCount = strlen($characters) - 1;
        $passphrase = '';

        for ($i = 0; $i < $length; $i++) {
            $passphrase .= $characters[random_int(0, $characterCount)];
        }

        return $passphrase;
    }

    /**
     * Create a passphrase based on the EFF Large Wordlist.
     *
     * @param  integer  $words
     * @return string
     */
    public function withWords(int $words = 5): string
    {
        $passphrase = '';
        $wordlist = include $this->ncrypt()->resourcePath('eff-large-wordlist.php');

        for ($i = 0; $i < $words; $i++) {
            if ($passphrase != '') {
                $passphrase .= '-';
            }

            $key = '';

            // roll five independed dices
            for ($dice = 0; $dice < 5; $dice++) {
                $key .= (string) random_int(1, 6);
            }

            // get the corresponding word
            $word = $wordlist[$key];

            // roll the word spelling
            if (random_int(1, 2) > 1) {
                $passphrase .= strtoupper($word);
            } else {
                $passphrase .= $word;
            }
        }

        return $passphrase;
    }
}
