<?php

use PasswordHelper\Policy;
use PasswordHelper\StrengthChecker;
use PHPUnit\Framework\TestCase;

class StrengthCheckerTest extends TestCase
{
    private StrengthChecker $checker;

    protected function setUp(): void
    {
        $this->checker = new StrengthChecker();
    }

    public function testEmptyPassword(): void
    {
        $this->assertEquals(1, $this->checker->checkStrength(''));
    }

    public function testVeryShortPassword(): void
    {
        $this->assertEquals(1, $this->checker->checkStrength('a'));
        $this->assertEquals(1, $this->checker->checkStrength('1'));
        $this->assertEquals(1, $this->checker->checkStrength('!'));
    }

    public function testLengthScoring(): void
    {
        // Use only 'a' characters so only length affects the score
        $short = str_repeat('a', 8);   // 8 chars
        $medium = str_repeat('a', 13); // 13 chars
        $long = str_repeat('a', 20);   // 20 chars

        $scoreShort = $this->checker->checkStrength($short);
        $scoreMedium = $this->checker->checkStrength($medium);
        $scoreLong = $this->checker->checkStrength($long);

        $this->assertGreaterThan($scoreShort, $scoreMedium, 'Medium password should have higher score than short');
        $this->assertGreaterThan($scoreMedium, $scoreLong, 'Longer password should have higher score than medium');
    }

    public function testCharacterVarietyScoring(): void
    {
        // Test that adding more character types increases the score
        $lower = 'abcdefgh';
        $lowerUpper = 'abcdefGh';
        $lowerUpperNum = 'abcDef1h';
        $lowerUpperNumSpec = 'abcDef1!';

        $scoreLower = $this->checker->checkStrength($lower);
        $scoreLowerUpper = $this->checker->checkStrength($lowerUpper);
        $scoreLowerUpperNum = $this->checker->checkStrength($lowerUpperNum);
        $scoreLowerUpperNumSpec = $this->checker->checkStrength($lowerUpperNumSpec);

        $this->assertGreaterThan($scoreLower, $scoreLowerUpper);
        $this->assertGreaterThan($scoreLowerUpper, $scoreLowerUpperNum);
        $this->assertGreaterThan($scoreLowerUpperNum, $scoreLowerUpperNumSpec);
    }

    public function testComplexityScoring(): void
    {
        // Test repeated characters
        $this->assertGreaterThan(
            $this->checker->checkStrength('aaaabcdef'),
            $this->checker->checkStrength('abcdefghi')
        );

        // Test sequential characters
        $this->assertGreaterThan(
            $this->checker->checkStrength('12345678'),
            $this->checker->checkStrength('13579246')
        );

        // Test common patterns
        $this->assertGreaterThan(
            $this->checker->checkStrength('password123'),
            $this->checker->checkStrength('p@ssw0rd123')
        );

        // Test dictionary words
        $this->assertGreaterThan(
            $this->checker->checkStrength('admin123'),
            $this->checker->checkStrength('adm1n123')
        );
    }

    public function testEntropyScoring(): void
    {
        // Test high entropy password
        $highEntropy = 'Kj#9mP$2vL5nX@8q';
        $this->assertGreaterThan(80, $this->checker->checkStrength($highEntropy));

        // Test low entropy password
        $lowEntropy = 'aaaaaaaaaaaaaaaa';
        $this->assertLessThan(70, $this->checker->checkStrength($lowEntropy));
    }

    public function testMaximumScore(): void
    {
        // Test that no password can exceed 100
        $this->assertEquals(100, $this->checker->checkStrength('Kj#9mP$2vL5nX@8qR7tY4wZ'));
    }

    public function testMinimumScore(): void
    {
        // Test that no password can go below 1
        $this->assertEquals(1, $this->checker->checkStrength('a'));
    }

    /**
     * Data provider for common password patterns test.
     *
     * @return array<int, array{0: string}>
     */
    public function commonPasswordProvider(): array
    {
        return [
            ['password123'],
            ['qwerty123'],
            ['admin123'],
            ['welcome123'],
            ['12345678']
        ];
    }

    /**
     * @dataProvider commonPasswordProvider
     */
    public function testCommonPasswordPatterns(string $password): void
    {
        $score = $this->checker->checkStrength($password);
        $this->assertLessThan(50, $score, "Password '$password' should have a low score");
    }

    /**
     * Data provider for strong password patterns test.
     *
     * @return array<int, array{0: string}>
     */
    public function strongPasswordProvider(): array
    {
        return [
            ['Kj#9mP$2vL5nX@8q'],
            ['P@ssw0rd!2024'],
            ['Str0ng!P@ssw0rd'],
            ['C0mpl3x!P@ssw0rd'],
            ['S3cur3!P@ssw0rd']
        ];
    }

    /**
     * @dataProvider strongPasswordProvider
     */
    public function testStrongPasswordPatterns(string $password): void
    {
        $score = $this->checker->checkStrength($password);
        $this->assertGreaterThan(70, $score, "Password '$password' should have a high score");
    }
}
