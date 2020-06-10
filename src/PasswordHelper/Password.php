<?php

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

    public function __construct($config = [])
    {
        $policy = new Policy($config);
        $this->checker = new StrengthChecker();
        $this->validator = new Validator($policy);
        $this->generator = new Generator($policy, $this->validator);
    }

    public function generate()
    {
        return $this->generator->generatePassword();
    }

    public function validateComplexity($password)
    {
        return $this->validator->isValidPassword($password);
    }

    public function checkStrength($password)
    {
        return $this->checker->checkStrength($password);
    }

    public function hash($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function verify($password, $hash)
    {
        return password_verify($password, $hash);
    }

    public function getInfo($hash)
    {
        return password_get_info($hash);
    }

    public function checkForRehash($hash)
    {
        return password_needs_rehash($hash, PASSWORD_DEFAULT);
    }
}