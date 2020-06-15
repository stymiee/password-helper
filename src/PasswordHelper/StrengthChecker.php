<?php

declare(strict_types=1);

namespace PasswordHelper;

class StrengthChecker
{
    /**
     * @var int Password strength score
     */
    protected $strength;

    /**
     * @var string[] Descriptions of levels of password strength
     */
    protected $levels = [
        'Very Weak', 'Weak', 'Good', 'Very Good', 'Strong', 'Very Strong'
    ];

    public function __construct()
    {
        $this->strength = 0;
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
        $score = 0;
        $score += min([strlen($password), Policy::MINIMUM_LENGTH]);
        $score += (int) (preg_match_all('/[a-z]/i', $password, $matches) >= 2);
        $score += (int) (bool) preg_match_all('/\d/', $password, $matches);
        $score += (int) (bool) preg_match_all('/[A-Z]/', $password, $matches);
        $score += (int) (bool) preg_match_all('/[a-z]/', $password, $matches);
        $score += (int) (bool) preg_match_all('/[^a-z\d ]/i', $password, $matches);
        $score = min([(int) ($score / 3), 6]);

        return $this->levels[$score];
    }
}
