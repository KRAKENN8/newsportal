<?php
class News {
    public static function getLast10News() {
        $query = "SELECT items.*, category.name AS category_name, COALESCE(c.comments_count, 0) AS comments_count 
                  FROM items 
                  LEFT JOIN category ON items.category_id = category.id 
                  LEFT JOIN (SELECT news_id, COUNT(id) AS comments_count FROM details GROUP BY news_id) AS c ON items.id = c.news_id 
                  ORDER BY items.id DESC LIMIT 6";
        $db = new Database();
        return $db->getAll($query);
    }

    public static function getAllNews() {
        $query = "SELECT items.*, category.name AS category_name, COALESCE(c.comments_count, 0) AS comments_count 
                  FROM items 
                  LEFT JOIN category ON items.category_id = category.id 
                  LEFT JOIN (SELECT news_id, COUNT(id) AS comments_count FROM details GROUP BY news_id) AS c ON items.id = c.news_id 
                  ORDER BY items.id DESC";
        $db = new Database();
        return $db->getAll($query);
    }

    public static function getNewsByCategoryID($id) {
        $query = "SELECT items.*, category.name AS category_name, COALESCE(c.comments_count, 0) AS comments_count 
                  FROM items 
                  LEFT JOIN category ON items.category_id = category.id 
                  LEFT JOIN (SELECT news_id, COUNT(id) AS comments_count FROM details GROUP BY news_id) AS c ON items.id = c.news_id 
                  WHERE items.category_id = :cat_id 
                  ORDER BY items.id DESC";
        $db = new Database();
        return $db->getAll($query, [':cat_id' => (int)$id]);
    }

    public static function getNewsByID($id) {
        $query = "SELECT items.*, category.name AS category_name, users.username AS author_name, COALESCE(c.comments_count, 0) AS comments_count 
                  FROM items 
                  LEFT JOIN category ON items.category_id = category.id 
                  LEFT JOIN users ON items.user_id = users.id 
                  LEFT JOIN (SELECT news_id, COUNT(id) AS comments_count FROM details GROUP BY news_id) AS c ON items.id = c.news_id 
                  WHERE items.id = :id";
        $db = new Database();
        return $db->getOne($query, [':id' => (int)$id]);
    }

    public static function searchNews($keyword) {
        $searchTerm = '%' . trim($keyword) . '%';
        $query = "SELECT items.*, category.name AS category_name, COALESCE(c.comments_count, 0) AS comments_count 
                  FROM items 
                  LEFT JOIN category ON items.category_id = category.id 
                  LEFT JOIN (SELECT news_id, COUNT(id) AS comments_count FROM details GROUP BY news_id) AS c ON items.id = c.news_id 
                  WHERE items.title LIKE :q1 OR items.text LIKE :q2 
                  ORDER BY items.id DESC";
        $db = new Database();
        return $db->getAll($query, [':q1' => $searchTerm, ':q2' => $searchTerm]);
    }

    /**
     * Paginated news listing with filtering and multi-criteria sorting
     */
    public static function getNewsPaginated($page = 1, $perPage = 6, $categoryId = null, $keyword = null, $sort = 'latest') {
        $page = max(1, (int)$page);
        $perPage = max(1, (int)$perPage);
        $offset = ($page - 1) * $perPage;

        $db = new Database();
        $where = [];
        $params = [];

        if ($categoryId !== null && (int)$categoryId > 0) {
            $where[] = "items.category_id = :cat_id";
            $params[':cat_id'] = (int)$categoryId;
        }

        if ($keyword !== null && trim($keyword) !== '') {
            $searchTerm = '%' . trim($keyword) . '%';
            $where[] = "(items.title LIKE :q1 OR items.text LIKE :q2)";
            $params[':q1'] = $searchTerm;
            $params[':q2'] = $searchTerm;
        }

        $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

        // Total matching items
        $countQuery = "SELECT COUNT(items.id) AS total FROM items $whereClause";
        $totalRow = $db->getOne($countQuery, $params);
        $total = isset($totalRow['total']) ? (int)$totalRow['total'] : 0;
        $totalPages = (int)ceil($total / $perPage);

        // Sorting
        $orderBy = "ORDER BY items.id DESC";
        if ($sort === 'popular') {
            $orderBy = "ORDER BY comments_count DESC, items.id DESC";
        } elseif ($sort === 'oldest') {
            $orderBy = "ORDER BY items.id ASC";
        }

        $query = "SELECT items.*, category.name AS category_name, COALESCE(c.comments_count, 0) AS comments_count 
                  FROM items 
                  LEFT JOIN category ON items.category_id = category.id 
                  LEFT JOIN (SELECT news_id, COUNT(id) AS comments_count FROM details GROUP BY news_id) AS c ON items.id = c.news_id 
                  $whereClause 
                  $orderBy 
                  LIMIT $perPage OFFSET $offset";

        $items = $db->getAll($query, $params);

        return [
            'items'       => $items,
            'total'       => $total,
            'page'        => $page,
            'perPage'     => $perPage,
            'totalPages'  => $totalPages,
            'sort'        => $sort
        ];
    }
}
?>
