<?php
class Profile {
    public static function updateUsername($userId, $newUsername) {
        $newUsername = trim($newUsername);

        if ($newUsername === '') {
            return array(0 => false, 1 => "Username cannot be empty.");
        }
        if (mb_strlen($newUsername) > 50) {
            return array(0 => false, 1 => "Username is too long (max 50 characters).");
        }

        $db = new Database();
        $conn = $db->connect();

        $stmt = $conn->prepare("UPDATE users SET username = :username WHERE id = :id");
        $stmt->execute([
            ':username' => $newUsername,
            ':id'       => $userId
        ]);

        return array(0 => true, 1 => "Username updated successfully.");
    }

    public static function getUserById($userId) {
        $db = new Database();
        $conn = $db->connect();

        $stmt = $conn->prepare("SELECT id, username, email FROM users WHERE id = :id");
        $stmt->execute([':id' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
