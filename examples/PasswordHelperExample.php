<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PasswordHelper\Generator;
use PasswordHelper\Policy;
use PasswordHelper\Validator;
use PasswordHelper\StrengthChecker;

// Create a password policy
$policy = new Policy(
    minimumLength: 10,
    maximumLength: 20,
    minimumCharacterTypes: 3,
    allowRepeatedCharacters: false,
    allowSequentialCharacters: false,
    allowCommonPatterns: false
);

// Create a password generator
$generator = new Generator(
    minLength: $policy->getMinimumLength(),
    maxLength: $policy->getMaximumLength()
);

// Create a password validator
$validator = new Validator($policy);

// Create a strength checker
$strengthChecker = new StrengthChecker();

// Generate a password
$password = $generator->generate();
echo "Generated Password: " . $password . PHP_EOL;

// Validate the password
$isValid = $validator->isValidPassword($password);
echo "Is Valid: " . ($isValid ? "Yes" : "No") . PHP_EOL;

// Check the password strength
$strength = $strengthChecker->checkStrength($password);
echo "Password Strength: " . $strength . PHP_EOL;

// Example of a weak password
$weakPassword = "password123";
$isWeakValid = $validator->isValidPassword($weakPassword);
$weakStrength = $strengthChecker->checkStrength($weakPassword);
echo "Weak Password: " . $weakPassword . PHP_EOL;
echo "Is Valid: " . ($isWeakValid ? "Yes" : "No") . PHP_EOL;
echo "Password Strength: " . $weakStrength . PHP_EOL;
