<?php

use PHPUnit\Framework\TestCase;

final class LoginTest extends TestCase
{
    protected function setUp(): void
    {
        useFreshTestDatabase();
        $_SESSION = [];
        $_POST = [];
    }

    protected function tearDown(): void
    {
        Database::$testConnection = null;
        $_SESSION = [];
        $_POST = [];
    }

    public function testReturnsDefaultErrorWhenFormWasNotSubmitted(): void
    {
        $result = Login::loginUser();

        $this->assertFalse($result[0]);
        $this->assertSame('Unknown error occurred.', $result[1]);
    }

    public function testRejectsInvalidEmailFormat(): void
    {
        $_POST = ['save' => '1', 'name' => 'not-an-email', 'password' => 'Secret123'];

        $result = Login::loginUser();

        $this->assertFalse($result[0]);
        $this->assertSame('Invalid email address format.', $result[1]);
    }

    public function testRejectsEmptyPassword(): void
    {
        $_POST = ['save' => '1', 'name' => 'admin@cyberpulse.ee', 'password' => ''];

        $result = Login::loginUser();

        $this->assertFalse($result[0]);
        $this->assertSame('Password cannot be empty.', $result[1]);
    }

    public function testRejectsUnknownEmail(): void
    {
        $_POST = ['save' => '1', 'name' => 'nobody@example.com', 'password' => 'Secret123'];

        $result = Login::loginUser();

        $this->assertFalse($result[0]);
        $this->assertSame('No user found with this email address.', $result[1]);
    }

    public function testRejectsWrongPassword(): void
    {
        $_POST = ['save' => '1', 'name' => 'admin@cyberpulse.ee', 'password' => 'WrongPassword'];

        $result = Login::loginUser();

        $this->assertFalse($result[0]);
        $this->assertSame('Incorrect password.', $result[1]);
    }

    public function testSuccessfulLoginSetsSessionAndReturnsSuccess(): void
    {
        $_POST = ['save' => '1', 'name' => 'admin@cyberpulse.ee', 'password' => 'Secret123'];

        $result = Login::loginUser();

        $this->assertTrue($result[0]);
        $this->assertSame('Login successful.', $result[1]);
        $this->assertSame(1, $_SESSION['user_id']);
        $this->assertSame('CyberAdmin', $_SESSION['username']);
    }
}
