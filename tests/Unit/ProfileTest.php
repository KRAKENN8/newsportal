<?php

use PHPUnit\Framework\TestCase;

final class ProfileTest extends TestCase
{
    protected function setUp(): void
    {
        useFreshTestDatabase();
    }

    protected function tearDown(): void
    {
        Database::$testConnection = null;
    }

    public function testGetUserByIdReturnsUserWithoutSensitiveFields(): void
    {
        $user = Profile::getUserById(1);

        $this->assertSame('CyberAdmin', $user['username']);
        $this->assertSame('admin@cyberpulse.ee', $user['email']);
        $this->assertArrayNotHasKey('password', $user);
        $this->assertArrayNotHasKey('pass', $user);
    }

    public function testGetUserByIdReturnsFalseForUnknownId(): void
    {
        $user = Profile::getUserById(999);

        $this->assertFalse($user);
    }

    public function testUpdateUsernameRejectsEmptyValue(): void
    {
        $result = Profile::updateUsername(1, '   ');

        $this->assertFalse($result[0]);
        $this->assertSame('Username cannot be empty.', $result[1]);
    }

    public function testUpdateUsernameRejectsTooLongValue(): void
    {
        $result = Profile::updateUsername(1, str_repeat('a', 51));

        $this->assertFalse($result[0]);
        $this->assertSame('Username is too long (max 50 characters).', $result[1]);
    }

    public function testUpdateUsernameAcceptsFiftyCharacterValue(): void
    {
        $result = Profile::updateUsername(1, str_repeat('a', 50));

        $this->assertTrue($result[0]);
    }

    public function testUpdateUsernamePersistsChangeAndTrimsWhitespace(): void
    {
        $result = Profile::updateUsername(1, '  NewName  ');

        $this->assertTrue($result[0]);
        $this->assertSame('Username updated successfully.', $result[1]);

        $user = Profile::getUserById(1);
        $this->assertSame('NewName', $user['username']);
    }
}
