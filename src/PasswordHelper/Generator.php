<?php

declare(strict_types=1);

namespace PasswordHelper;

class Generator
{
    /**
     * @var Policy Contains password policy logic
     */
    protected $policy;

    /**
     * @var Validator
     */
    protected $validator;

    public function __construct(Policy $policy, Validator $validator)
    {
        $this->policy = $policy;
        $this->validator = $validator;
    }

    /**
     * Generates a random password that meets the criteria set forth by the password policy
     *
     * @return string
     *
     * @throws \Exception
     */
    public function generatePassword(): string
    {
        $characters = $this->getAvailableCharacters();
        $passwordLength = $this->policy->getMinimumLength();

        $password = '';
        for ($i = 0; $i < $passwordLength; $i++) {
            $password .= $this->getRandomCharacter($characters);
        }
        if ($this->validator->isValidPassword($password)) {
            return $password;
        }

        return $this->generatePassword();
    }

    /**
     * Gets the available characters as set forth by the password policy
     *
     * @return array
     */
    protected function getAvailableCharacters(): array
    {
        $chars = [];
        if ($this->policy->getMinimumDigits()) {
            $chars = array_merge($chars, range(0, 9));
        }
        if ($this->policy->getMinimumSpecialChars()) {
            $chars = array_merge($chars, str_split('^_~@#$%&-=+{};:<>'));
        }
        if ($this->policy->getMinimumLetters()) {
            if ($this->policy->getMinimumLowercase()) {
                $chars = array_merge($chars, range('a', 'z'));
            }
            if ($this->policy->getMinimumUppercase()) {
                $chars = array_merge($chars, range('A', 'Z'));
            }
        }

        return $chars;
    }

    /**
     * Gets a random character from the array of available characters. Can return the same character more than once
     * if called multiple times.
     *
     * @param array $chars
     *
     * @return string
     *
     * @throws \Exception
     */
    protected function getRandomCharacter(array $chars): string
    {
        shuffle($chars);
        return (string) $chars[random_int(0, count($chars) - 1)];
    }
}
