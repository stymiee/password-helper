# Password Helper (password-helper)

A PHP library that makes using best practices with passwords easy _by default_.

## Requirements

- PHP 7.2+

## Installation

Simply add a dependency on `stymiee/password-helper` to your project's `composer.json` file if you 
use [Composer](http://getcomposer.org/) to manage the dependencies of your project.

Here is a minimal example of a `composer.json` file that just defines a dependency on Password Helper:

    {
        "require": {
            "stymiee/password-helper": "^1"
        }
    }

## Basic Usage

### Configuration

To configure your Password Helper to suit your business requirements, you can set your password policy when 
creating your Password Helper object. There are six factors you can configure be required (or not) and, if required, 
the minimum criteria for that password characteristic. They are:
    
1. **minimumLength** - Sets the minimum length a password must be. (Default: 10)
2. **minimumSpecialChars** - Sets the minimum number of special characters required to be in the password (Default: 1)
3. **minimumUppercase** - Sets the minimum number of uppercase letters required to be in the password (Default: 1)
4. **minimumLowercase** - Sets the minimum number of lowercase letters required to be in the password (Default: 1)
5. **minimumLetters** - Sets the minimum number of total alphabetic characters required to be in the password (Default: 1)
6. **minimumDigits** - Sets the minimum number of numbers required to be in the password (Default: 1)

If you do not pass any custom policy rules when creating your Password Helper it will default to the values listed above.

    $passwordHelper = new Password();
    
is equivalent to:

    $passwordHelper = new Password([
        'minimumLetters' => 1,
        'minimumDigits' => 1,
        'minimumLowercase' => 1,
        'minimumUppercase' => 1,
        'minimumSpecialChars' => 1,
        'minimumLength' => 10 
    ]);

To modify a policy you can pass it by name, with its custom value, to the constructor. The code below sets all the 
rules to require two of each type and sets a minimum password length of twelve characters.

    $passwordHelper = new Password([
        'minimumLetters' => 2,
        'minimumDigits' => 2,
        'minimumLowercase' => 2,
        'minimumUppercase' => 2,
        'minimumSpecialChars' => 2,
        'minimumLength' => 12 
    ]);
    
You only need to pass a custom value when you change its value from the default value. The code below only changes the
values for `minimumDigits` and `minimumLength`.
    
    $passwordHelper = new Password([
        'minimumDigits' => 2,
        'minimumLength' => 12 
    ]);
    
To remove a requirement give it a value of zero.
    
    $passwordHelper = new Password([
        'minimumSpecialChars' => 0  // Special characters are not required
    ]);
        
### Generate a new password

    $password = (\PasswordHelper\new Password())->generate(); // 8TpKC>&nQA

### Validate a password is acceptable under your password policy

    $password = \PasswordHelper\new Password();
    echo var_dump($password->validateComplexity('!aa34sDDdfg7dfgdsfg2gg'));
    echo var_dump($password->validateComplexity('1234'));
    
**Outputs**

    true
    false    

### Check the strength of a password

    $password = \PasswordHelper\new Password();
    echo $password->checkStrength('a');
    echo $password->checkStrength('qr193');
    echo $password->checkStrength('8TpKC>&nQA');

**Outputs**

    Very Weak
    Good
    Very Strong

### Hash a password

    $hashedPassword = (\PasswordHelper\new Password())->hash('secret1234');

### Validate a password

    $password = \PasswordHelper\new Password();
    if ($password->verify('secret1234', $row['password_hash'])) {
        // Let them in
    } else {
        // Authentication failure
    }   

### Update the hash of a password

    $password = \PasswordHelper\new Password();
    if ($password->checkForRehash($row['password_hash'])) {
        $newHash = $password->hash('secret1234');
        // ... then save the new hash ...
    }

## Support

If you require assistance using this library start by viewing the [HELP.md](HELP.md) file included in this package. It 
includes common problems and their solutions.

If you need additional assistance, I can be found at Stack Overflow. Be sure when you
[ask a question](http://stackoverflow.com/questions/ask?tags=php,password) pertaining to the usage of
this library be sure to tag your question with the **PHP** and **password** tags. Make sure you follow their
[guide for asking a good question](http://stackoverflow.com/help/how-to-ask) as poorly asked questions will be closed, 
and I will not be able to assist you.

A good question will include all the following:
- A description of the problem (what are you trying to do? what results are you expecting? what results are you actually getting?)
- The code you are using (only post the relevant code)
- Any error message(s) you are getting

**Do not use Stack Overflow to report bugs.** Bugs may be reported [here](https://github.com/stymiee/password-helper/issues/new).
