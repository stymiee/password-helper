<?php

declare(strict_types=1);

namespace PasswordHelper;

/**
 * @codeCoverageIgnore
 */
class Password
{
    /**
     * @var StrengthChecker
     */
    protected $checker;

    /**
     * @var Generator
     */
    protected $generator;

    /**
     * @var Validator
     */
    protected $validator;

    public function __construct(array $config = [])
    {
        $policy = new Policy($config);
        $this->checker = new StrengthChecker();
        $this->validator = new Validator($policy);
        $this->generator = new Generator($policy, $this->validator);
    }

    /**
     * Generates a password that satisfies the password policy
     *
     * @return string
     *
     * @throws \Exception
     */
    public function generate(): string
    {
        return $this->generator->generatePassword();
    }

    /**
     * Validates that a password satisfies the password policy
     *
     * @param string $password
     *
     * @return bool
     */
    public function validateComplexity(string $password): bool
    {
        return $this->validator->isValidPassword($password);
    }

    /**
     * Determines the strength of a given password
     *
     * @param string $password
     *
     * @return string
     */
    public function checkStrength(string $password): string
    {
        return $this->checker->checkStrength($password);
    }

    /**
     * Creates a password hash.
     *
     * @see https://secure.php.net/manual/en/function.password-hash.php
     *
     * @param string $password The user's password.

     * @return string
     */
    public function hash(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Checks if the given hash matches the given options.
     *
     * @see https://secure.php.net/manual/en/function.password-verify.php
     *
     * @param string $password The user's password.
     * @param string $hash A hash created by password_hash().
     *
     * @return bool Returns TRUE if the password and hash match, or FALSE otherwise.
     */
    public function verify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * Returns information about the given hash
     *
     * @see https://secure.php.net/manual/en/function.password-get-info.php
     *
     * @param string $hash A hash created by password_hash()
     *
     * @return array Returns an associative array with three elements:
     *     - algo, which will match a password algorithm constant
     *     - algoName, which has the human readable name of the algorithm
     *     - options which includes the options provided when calling password_hash()
     */
    public function getInfo(string $hash): array
    {
        return password_get_info($hash);
    }

    /**
     * Checks if the given hash matches the given options.
     *
     * @see https://secure.php.net/manual/en/function.password-needs-rehash.php
     *
     * @param string $hash A hash created by password_hash().
     *
     * @return bool Returns TRUE if the hash should be rehashed to match the given algo and options, or FALSE otherwise.
     */
    public function checkForRehash(string $hash): bool
    {
        return password_needs_rehash($hash, PASSWORD_DEFAULT);
    }
}