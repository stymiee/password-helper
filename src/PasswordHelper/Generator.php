<?php

declare(strict_types=1);

namespace PasswordHelper;

/**
 * Generates secure random passwords that meet specified policy requirements.
 *
 * This class is responsible for creating passwords that satisfy all the criteria
 * defined in the password policy, including length, character types, and minimum
 * requirements for each character category.
 *
 * @package PasswordHelper
 */
class Generator
{
    /**
     * Password policy configuration.
     *
     * @var Policy
     */
    protected $policy;

    /**
     * Password validator instance.
     *
     * @var Validator
     */
    protected $validator;

    /**
     * Creates a new password generator with the specified policy and validator.
     *
     * @param Policy $policy The password policy to follow
     * @param Validator $validator The validator to ensure generated passwords meet requirements
     */
    public function __construct(Policy $policy, Validator $validator)
    {
        $this->policy = $policy;
        $this->validator = $validator;
    }

    /**
     * Generates a random password that meets all criteria set forth by the password policy.
     *
     * The method will recursively generate passwords until one is found that satisfies
     * all policy requirements. This ensures that the returned password always meets
     * the minimum criteria for length and character types.
     *
     * @return string A randomly generated password meeting all policy requirements
     * @throws \Exception If random number generation fails
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
     * Gets the available characters based on the password policy requirements.
     *
     * This method builds an array of characters that can be used in password
     * generation based on the minimum requirements for each character type
     * (digits, special characters, lowercase, uppercase).
     *
     * @return array<int, string> Array of available characters for password generation
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
     * Gets a random character from the array of available characters.
     *
     * This method uses PHP's cryptographically secure random number generator
     * to select a random character from the available character set.
     *
     * @param array<int, string> $chars Array of available characters
     * @return string A randomly selected character
     * @throws \Exception If random number generation fails
     */
    protected function getRandomCharacter(array $chars): string
    {
        shuffle($chars);
        return $chars[random_int(0, count($chars) - 1)];
    }
}
