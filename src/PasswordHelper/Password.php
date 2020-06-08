<?php

declare(strict_types=1);

namespace PasswordHelper;

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

    public function generate(): string
    {
        return $this->generator->generatePassword();
    }

    public function validateComplexity(string $password): bool
    {
        return $this->validator->isValidPassword($password);
    }

    public function checkStrength(string $password): string
    {
        return $this->checker->checkStrength($password);
    }

    public function hash(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function verify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}