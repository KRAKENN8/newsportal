<?php
class Login {
    public static function loginUser() {
        $controll = array(0 => false, 1 => 'Unknown error occurred.');

        if (isset($_POST['save'])) {
            if (class_exists('Security')) {
                $throttle = Security::checkRateLimit('login', 5, 300);
                if ($throttle['isBlocked']) {
                    return array(0 => false, 1 => "Too many failed login attempts. Please try again in " . $throttle['remainingSeconds'] . " seconds.");
                }
            }

            $email = filter_var($_POST['name'] ?? '', FILTER_VALIDATE_EMAIL);
            $password = isset($_POST['password']) ? trim($_POST['password']) : '';

            if (!$email) {
                return array(0 => false, 1 => "Invalid email address format.");
            }
            if (!$password) {
                return array(0 => false, 1 => "Password cannot be empty.");
            }

            $db = new Database();
            $conn = $db->connect();

            $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = :email");
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                if (password_verify($password, $user['password'])) {
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }
                    if (!headers_sent()) {
                        @session_regenerate_id(true);
                    }
                    if (class_exists('Security')) {
                        Security::clearRateLimit('login');
                    }
                    $_SESSION['user_id']  = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $controll = array(0 => true, 1 => "Login successful.");
                } else {
                    if (class_exists('Security')) {
                        Security::recordFailedAttempt('login');
                    }
                    $controll = array(0 => false, 1 => "Incorrect password.");
                }
            } else {
                if (class_exists('Security')) {
                    Security::recordFailedAttempt('login');
                }
                $controll = array(0 => false, 1 => "No user found with this email address.");
            }
        }

        return $controll;
    }
}
