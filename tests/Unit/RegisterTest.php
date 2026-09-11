<?php

use PHPUnit\Framework\TestCase;

final class RegisterTest extends TestCase
{
    protected function setUp(): void
    {
        useFreshTestDatabase();
        $_POST = [];
    }

    protected function tearDown(): void
    {
        Database::$testConnection = null;
        $_POST = [];
    }

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'save' => '1',
            'name' => 'Bob Newuser',
            'email' => 'bob@example.com',
            'password' => 'Secret123',
            'confirm' => 'Secret123',
        ], $overrides);
    }

    public function testReturnsDefaultErrorWhenFormWasNotSubmitted(): void
    {
        $result = Register::registerUser();

        $this->assertFalse($result[0]);
        $this->assertSame('Unknown error occurred.', $result[1]);
    }

    public function testRejectsEmptyName(): void
    {
        $_POST = $this->validPayload(['name' => '  ']);

        $result = Register::registerUser();

        $this->assertFalse($result[0]);
        $this->assertStringContainsString('Name cannot be empty.', $result[1]);
    }

    public function testRejectsInvalidEmail(): void
    {
        $_POST = $this->validPayload(['email' => 'not-an-email']);

        $result = Register::registerUser();

        $this->assertFalse($result[0]);
        $this->assertStringContainsString('Invalid email address format.', $result[1]);
    }

    public function testRejectsShortPassword(): void
    {
        $_POST = $this->validPayload(['password' => '123', 'confirm' => '123']);

        $result = Register::registerUser();

        $this->assertFalse($result[0]);
        $this->assertStringContainsString('Password must be at least 6 characters long.', $result[1]);
    }

    public function testRejectsMismatchedPasswords(): void
    {
        $_POST = $this->validPayload(['confirm' => 'Different1']);

        $result = Register::registerUser();

        $this->assertFalse($result[0]);
        $this->assertStringContainsString('Passwords do not match.', $result[1]);
    }

    public function testRejectsDuplicateEmail(): void
    {
        $_POST = $this->validPayload(['email' => 'admin@cyberpulse.ee']);

        $result = Register::registerUser();

        $this->assertFalse($result[0]);
        $this->assertSame('A user with this email address already exists.', $result[1]);
    }

    public function testSuccessfulRegistrationCreatesUser(): void
    {
        $_POST = $this->validPayload();

        $result = Register::registerUser();

        $this->assertTrue($result[0]);

        $stmt = Database::$testConnection->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute([':email' => 'bob@example.com']);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertNotFalse($user);
        $this->assertSame('Bob Newuser', $user['username']);
        $this->assertSame('user', $user['status']);
        $this->assertTrue(password_verify('Secret123', $user['password']));
    }

    public function testAccumulatesMultipleValidationErrors(): void
    {
        $_POST = [
            'save' => '1',
            'name' => '',
            'email' => 'bad-email',
            'password' => '123',
            'confirm' => '456',
        ];

        $result = Register::registerUser();

        $this->assertFalse($result[0]);
        $this->assertStringContainsString('Name cannot be empty.', $result[1]);
        $this->assertStringContainsString('Invalid email address format.', $result[1]);
        $this->assertStringContainsString('Password must be at least 6 characters long.', $result[1]);
    }
}
