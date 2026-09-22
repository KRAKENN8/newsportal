<?php
class modelAdminNews {
    public static function getNewsList() {
        $query = "SELECT items.*, category.name, users.username FROM items, category, users WHERE items.category_id=category.id AND items.user_id=users.id ORDER BY items.id DESC";
        $db = new Database();
        $arr = $db->getAll($query);
        return $arr;
    }

    // Add
    public static function getNewsAdd() {
        $test = false;
        if (isset($_POST['save'])) {
            if (isset($_POST['title'], $_POST['text'], $_POST['idCategory'])) {
                $title = trim($_POST['title']);
                $text = trim($_POST['text']);
                $idCategory = (int)$_POST['idCategory'];
                $userId = isset($_SESSION['userId']) ? (int)$_SESSION['userId'] : 1;

                $image = '';
                if (isset($_FILES['picture'])) {
                    $uploaded = self::validateAndProcessImageUpload($_FILES['picture']);
                    if ($uploaded !== null) {
                        $image = $uploaded;
                    }
                }
                if (empty($image)) {
                    // Default SVG placeholder
                    $image = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 450" width="100%" height="100%"><rect width="800" height="450" fill="#111827"/><text x="400" y="235" font-family="sans-serif" font-size="28" fill="#00f0ff" text-anchor="middle">CYBERPULSE // NEWS</text></svg>';
                }

                $db = new Database();
                $conn = $db->connect();
                $stmt = $conn->prepare("INSERT INTO items (title, text, picture, category_id, user_id) VALUES (:title, :text, :picture, :category_id, :user_id)");
                $test = $stmt->execute([
                    ':title' => $title,
                    ':text' => $text,
                    ':picture' => $image,
                    ':category_id' => $idCategory,
                    ':user_id' => $userId
                ]);
            }
        }
        return $test;
    }

    /**
     * Validate and process image upload with strict MIME & size limits
     */
    public static function validateAndProcessImageUpload(array $file): ?string {
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return null;
        }

        // 1. Enforce max file size: 5 MB
        $maxSizeBytes = 5 * 1024 * 1024;
        if (($file['size'] ?? 0) > $maxSizeBytes || filesize($file['tmp_name']) > $maxSizeBytes) {
            $_SESSION['adminFlash'] = 'Upload rejected: Image exceeds maximum allowed size of 5 MB.';
            return null;
        }

        // 2. Validate MIME type using finfo
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        $allowedMimes = [
            'image/jpeg',
            'image/png',
            'image/webp',
            'image/gif'
        ];

        $content = file_get_contents($file['tmp_name']);
        if (in_array($mime, $allowedMimes, true)) {
            return $content;
        }

        // If SVG, perform sanitization to prevent Stored XSS
        if ($mime === 'image/svg+xml' || (strpos($content, '<svg') !== false)) {
            $cleanSvg = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $content);
            $cleanSvg = preg_replace('/(on\w+\s*=\s*["\'][^"\']*["\'])/i', '', $cleanSvg);
            $cleanSvg = preg_replace('/javascript:/i', 'blocked:', $cleanSvg);
            return $cleanSvg;
        }

        $_SESSION['adminFlash'] = 'Upload rejected: Only JPEG, PNG, WEBP, GIF, and sanitized SVG images are allowed.';
        return null;
    }

    // news detail id
    public static function getNewsDetail($id) {
        $safeId = (int)$id;
        $query = "SELECT items.*, category.name, users.username FROM items, category, users WHERE items.category_id=category.id AND items.user_id=users.id AND items.id = :id";
        $db = new Database();
        $arr = $db->getOne($query, [':id' => $safeId]);
        return $arr;
    }

    // news edit
    public static function getNewsEdit($id) {
        $test = false;
        $safeId = (int)$id;
        if (isset($_POST['save'])) {
            if (isset($_POST['title'], $_POST['text'], $_POST['idCategory'])) {
                $title = trim($_POST['title']);
                $text = trim($_POST['text']);
                $idCategory = (int)$_POST['idCategory'];

                $db = new Database();
                $conn = $db->connect();

                $uploaded = null;
                if (isset($_FILES['picture'])) {
                    $uploaded = self::validateAndProcessImageUpload($_FILES['picture']);
                }

                if ($uploaded !== null) {
                    $stmt = $conn->prepare("UPDATE items SET title = :title, text = :text, picture = :picture, category_id = :category_id WHERE id = :id");
                    $test = $stmt->execute([
                        ':title' => $title,
                        ':text' => $text,
                        ':picture' => $uploaded,
                        ':category_id' => $idCategory,
                        ':id' => $safeId
                    ]);
                } else {
                    $stmt = $conn->prepare("UPDATE items SET title = :title, text = :text, category_id = :category_id WHERE id = :id");
                    $test = $stmt->execute([
                        ':title' => $title,
                        ':text' => $text,
                        ':category_id' => $idCategory,
                        ':id' => $safeId
                    ]);
                }
            }
        }
        return $test;
    }

    // news delete
    public static function getNewsDelete($id) {
        $test = false;
        $safeId = (int)$id;
        if (isset($_POST['save']) || isset($_GET['confirm']) || $_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = new Database();
            $conn = $db->connect();
            // Also delete associated comments
            $stmtComments = $conn->prepare("DELETE FROM details WHERE news_id = :id");
            $stmtComments->execute([':id' => $safeId]);

            $stmt = $conn->prepare("DELETE FROM items WHERE id = :id");
            $test = $stmt->execute([':id' => $safeId]);
        }
        return $test;
    }
}
?>