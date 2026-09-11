<?php

use PHPUnit\Framework\TestCase;

/**
 * @see ControllerNewsTest for why exit()-ing flows (InsertComment, unauthenticated
 *      profile/logout) are covered by Playwright E2E instead of here.
 */
final class ControllerAuthTest extends TestCase
{
    protected function setUp(): void
    {
        useFreshTestDatabase();
        $_SESSION = [];
        $_POST = [];
        $_SERVER['REQUEST_METHOD'] = 'GET';
    }

    protected function tearDown(): void
    {
        Database::$testConnection = null;
        $_SESSION = [];
        $_POST = [];
    }

    private function captureOutput(callable $fn): string
    {
        $level = ob_get_level();
        ob_start();

        try {
            $fn();
            return ob_get_contents();
        } finally {
            while (ob_get_level() > $level) {
                ob_end_clean();
            }
        }
    }

    public function testRegisterFormRendersWithoutErrors(): void
    {
        $output = $this->captureOutput(fn() => Controller::registerForm());

        $this->assertNotSame('', trim($output));
    }

    public function testRegisterUserWithValidDataCreatesAccount(): void
    {
        $_POST = [
            'save' => '1',
            'name' => 'Charlie',
            'email' => 'charlie@example.com',
            'password' => 'Secret123',
            'confirm' => 'Secret123',
        ];

        $this->captureOutput(fn() => Controller::registerUser());

        $stmt = Database::$testConnection->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute([':email' => 'charlie@example.com']);
        $this->assertNotFalse($stmt->fetch());
    }

    public function testRegisterUserWithInvalidDataDoesNotCreateAccount(): void
    {
        $_POST = [
            'save' => '1',
            'name' => '',
            'email' => 'not-an-email',
            'password' => '123',
            'confirm' => '456',
        ];

        $this->captureOutput(fn() => Controller::registerUser());

        $stmt = Database::$testConnection->query('SELECT COUNT(*) as c FROM users');
        $count = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertSame(2, (int)$count['c']); // only the two seeded users remain
    }

    public function testFormLoginRendersWithoutErrors(): void
    {
        $output = $this->captureOutput(fn() => Controller::formLogin());

        $this->assertNotSame('', trim($output));
    }

    public function testLoginUserWithValidCredentialsEstablishesSession(): void
    {
        $_POST = ['save' => '1', 'name' => 'admin@cyberpulse.ee', 'password' => 'Secret123'];

        $this->captureOutput(fn() => Controller::loginUser());

        $this->assertSame(1, $_SESSION['user_id']);
    }

    public function testLoginUserWithInvalidCredentialsDoesNotEstablishSession(): void
    {
        $_POST = ['save' => '1', 'name' => 'admin@cyberpulse.ee', 'password' => 'WrongPassword'];

        $this->captureOutput(fn() => Controller::loginUser());

        $this->assertArrayNotHasKey('user_id', $_SESSION);
    }

    public function testProfileRendersCurrentUserDataForLoggedInUser(): void
    {
        $_SESSION['user_id'] = 1;

        $output = $this->captureOutput(fn() => Controller::profile());

        $this->assertStringContainsString('CyberAdmin', $output);
    }

    public function testProfileUpdatesUsernameOnPost(): void
    {
        $_SESSION['user_id'] = 1;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['username'] = 'RenamedAdmin';

        $this->captureOutput(fn() => Controller::profile());

        $this->assertSame('RenamedAdmin', $_SESSION['username']);
        $user = Profile::getUserById(1);
        $this->assertSame('RenamedAdmin', $user['username']);
    }

    public function testProfileIgnoresInvalidUsernameOnPost(): void
    {
        $_SESSION['user_id'] = 1;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['username'] = '   ';

        $this->captureOutput(fn() => Controller::profile());

        $user = Profile::getUserById(1);
        $this->assertSame('CyberAdmin', $user['username']); // unchanged
    }
}
