<?php
class Database {
    private $conn;
    private $host;
    private $user;
    private $password;
    private $baseName;

    /**
     * Global hook used ONLY by the test-suite. When set, every new
     * Database() instance will use this PDO connection instead of opening
     * a real MySQL connection. Production code never touches this.
     */
    public static ?PDO $testConnection = null;

    /**
     * @param PDO|null $pdo Optional PDO connection to inject directly
     *                      (used by unit tests). Production code should
     *                      keep calling `new Database()` with no arguments.
     */
    function __construct(?PDO $pdo = null) {
        if ($pdo !== null) {
            $this->conn = $pdo;
            return;
        }

        if (self::$testConnection !== null) {
            $this->conn = self::$testConnection;
            return;
        }

        $this->host = 'localhost';
        $this->user = 'root';
        $this->password = '';
        $this->baseName = 'cyberpulse';
        $this->connect();
    }

    function __destruct() {
        $this->disconnect();
    }

    function connect() {
        if (!$this->conn) {
            try {
                $this->conn = new PDO(
                    'mysql:host='.$this->host.';dbname='.$this->baseName.';charset=utf8mb4',
                    $this->user,
                    $this->password,
                    array(
                        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4',
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                    )
                );
            } catch (Exception $e) {
                die('Connection failed: ' . $e->getMessage());
            }
        }
        return $this->conn;
    }

    function disconnect() {
        if ($this->conn) {
            $this->conn = null;
        }
    }

    function getOne($query) {
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $response = $stmt->fetch();
        return $response;
    }

    function getAll($query) {
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $response = $stmt->fetchAll();
        return $response;
    }

    function executeRun($query) {
        $response = $this->conn->exec($query);
        return $response;
    }
}
?>
