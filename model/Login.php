<?php
class Login {
    public static function loginUser() {
        $controll = array(0 => false, 1 => 'Unknown error occurred.');

        if (isset($_POST['save'])) {
            $email = filter_input(INPUT_POST, 'name', FILTER_VALIDATE_EMAIL);
            $password = isset($_POST['password']) ? trim($_POST['password']) : '';

            // Проверка на пустые/некорректные поля
            if (!$email) {
                return array(0 => false, 1 => "Invalid email address format.");
            }
            if (!$password) {
                return array(0 => false, 1 => "Password cannot be empty.");
            }

            // Подключение к базе данных
            $db = new Database();
            $conn = $db->connect();

            // Проверка существования пользователя
            $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = :email");
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Проверка пароля
                if (password_verify($password, $user['password'])) {
                    // Успешный вход
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }
                    $_SESSION['user_id']  = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $controll = array(0 => true, 1 => "Login successful.");
                } else {
                    // Неверный пароль
                    $controll = array(0 => false, 1 => "Incorrect password.");
                }
            } else {
                // Пользователь не найден
                $controll = array(0 => false, 1 => "No user found with this email address.");
            }
        }

        return $controll;
    }
}