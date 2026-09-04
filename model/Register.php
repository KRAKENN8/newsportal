<?php
class Register {
    public static function registerUser() {
        $controll = array(0 => false, 1 => 'Unknown error occurred.');
        if (isset($_POST['save'])) {
            $errorString = "";
            $name = isset($_POST['name']) ? trim($_POST['name']) : '';
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            if (!$name) {
                $errorString .= "Name cannot be empty.<br/>";
            }
            if (!$email) {
                $errorString .= "Invalid email address format.<br/>";
            }
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            $confirm = isset($_POST['confirm']) ? $_POST['confirm'] : '';
            if (!$password || !$confirm || mb_strlen($password) < 6) {
                $errorString .= "Password must be at least 6 characters long.<br/>";
            }
            if ($password !== $confirm) {
                $errorString .= "Passwords do not match.<br/>";
            }

            if (empty($errorString)) {
                $db = new Database();
                $conn = $db->connect();

                // Check duplicate email
                $checkStmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
                $checkStmt->execute([':email' => $email]);
                if ($checkStmt->fetch()) {
                    return array(0 => false, 1 => "A user with this email address already exists.");
                }

                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $date = date("Y-m-d");
                $stmt = $conn->prepare("INSERT INTO users (username, email, password, status, registration_date, pass) VALUES (:username, :email, :password, 'user', :reg_date, :pass)");
                $item = $stmt->execute([
                    ':username' => $name,
                    ':email' => $email,
                    ':password' => $passwordHash,
                    ':reg_date' => $date,
                    ':pass' => $password
                ]);

                if ($item) {
                    $controll = array(0 => true);
                } else {
                    $controll = array(0 => false, 1 => 'Failed to save user in the database.');
                }
            } else {
                $controll = array(0 => false, 1 => $errorString);
            }
        }
        return $controll;
    }
}
?>