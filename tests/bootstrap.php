<?php
// Several models call session_start() (Login, Comments, Controller::profile).
// In CLI SAPI, if ANY output has already been echoed before session_start()
// runs, PHP raises "Session cannot be started after headers have already
// been sent". Wrapping the whole test run in a single output buffer avoids
// that entirely, regardless of what the test runner itself prints.
if (ob_get_level() === 0) {
    ob_start();
}

// Controller.php uses paths like 'view/start.php' relative to the CWD the
// script is run from (exactly like index.php does in production, since
// Apache's CWD is the document root). Force the CWD to the project root so
// those includes resolve correctly no matter where `phpunit` is invoked from.
chdir(__DIR__ . '/..');

// Composer autoloader (installed via `composer install`)
require_once __DIR__ . '/../vendor/autoload.php';

// Load production classes exactly like index.php does
require_once __DIR__ . '/../inc/Database.php';
require_once __DIR__ . '/../model/Category.php';
require_once __DIR__ . '/../model/News.php';
require_once __DIR__ . '/../model/Comments.php';
require_once __DIR__ . '/../model/Register.php';
require_once __DIR__ . '/../model/Login.php';
require_once __DIR__ . '/../model/Profile.php';
require_once __DIR__ . '/../controller/Controller.php';

/**
 * Test-only helpers shared by Unit and Integration tests.
 *
 * We use a real (but in-memory, per-test) SQLite database instead of mocking
 * PDOStatement chains. This keeps the tests fast, fully isolated, and free of
 * any real MySQL server dependency, while still exercising the exact SQL
 * written in the models. The schema below mirrors cyberpulse.sql.
 */
final class TestDb
{
    public static function fresh(): PDO
    {
        $pdo = new PDO('sqlite::memory:');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $pdo->exec('
            CREATE TABLE category (
                id INTEGER PRIMARY KEY,
                name VARCHAR(50) NOT NULL
            )
        ');

        $pdo->exec('
            CREATE TABLE users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username VARCHAR(100) NOT NULL,
                email VARCHAR(50) NOT NULL,
                password VARCHAR(255) NOT NULL,
                status VARCHAR(20) NOT NULL,
                registration_date DATE NOT NULL,
                pass VARCHAR(255) NOT NULL
            )
        ');

        $pdo->exec('
            CREATE TABLE news (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title VARCHAR(255) NOT NULL,
                text TEXT NOT NULL,
                picture BLOB,
                category_id INTEGER NOT NULL,
                user_id INTEGER NOT NULL
            )
        ');

        $pdo->exec('
            CREATE TABLE comments (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id INTEGER NOT NULL,
                news_id INTEGER NOT NULL,
                text VARCHAR(500) NOT NULL,
                date DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ');

        return $pdo;
    }

    /** Seeds a minimal, deterministic dataset used across tests. */
    public static function seed(PDO $pdo): void
    {
        $pdo->exec("INSERT INTO category (id, name) VALUES
            (1, 'AI & Neural Networks'),
            (2, 'Hardware & Gadgets'),
            (3, 'Cybersecurity')");

        // password for both seeded users is: Secret123
        $hash = password_hash('Secret123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (id, username, email, password, status, registration_date, pass)
            VALUES (:id, :username, :email, :password, 'user', '2024-01-01', 'Secret123')");
        $stmt->execute([':id' => 1, ':username' => 'CyberAdmin', ':email' => 'admin@cyberpulse.ee', ':password' => $hash]);
        $stmt->execute([':id' => 2, ':username' => 'Alice', ':email' => 'alice@example.com', ':password' => $hash]);

        $pdo->exec("INSERT INTO news (id, title, text, picture, category_id, user_id) VALUES
            (1, 'Quantum Computing Leap', 'Physicists achieve a breakthrough.', '', 1, 1),
            (2, 'New Firewall Released', 'A new open-source firewall was released.', '', 3, 1)");

        $pdo->exec("INSERT INTO comments (id, user_id, news_id, text, date) VALUES
            (1, 2, 1, 'Amazing progress!', '2024-01-02 10:00:00')");
    }
}

/**
 * Point Database's static test hook at a fresh in-memory DB and return it,
 * so each test gets full isolation without touching a real MySQL server.
 */
function useFreshTestDatabase(bool $seeded = true): PDO
{
    $pdo = TestDb::fresh();
    if ($seeded) {
        TestDb::seed($pdo);
    }
    Database::$testConnection = $pdo;
    return $pdo;
}
