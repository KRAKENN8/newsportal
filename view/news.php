<?php
class ViewNews {
    /**
     * Helper to get valid data-uri for blob image (supports SVG, PNG, JPEG)
     */
    public static function getImageSrc($blob) {
        if (empty($blob)) {
            return 'data:image/svg+xml;base64,' . base64_encode(
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 450" width="100%" height="100%"><rect width="800" height="450" fill="#111827"/><text x="400" y="235" font-family="sans-serif" font-size="28" fill="#00f0ff" text-anchor="middle">CYBERPULSE</text></svg>'
            );
        }
        if (strpos($blob, '<svg') !== false) {
            return 'data:image/svg+xml;base64,' . base64_encode($blob);
        }
        if (substr($blob, 0, 4) === "\x89PNG") {
            return 'data:image/png;base64,' . base64_encode($blob);
        }
        return 'data:image/jpeg;base64,' . base64_encode($blob);
    }

    /**
     * Estimate reading time based on word count
     */
    public static function getReadingTime($text) {
        $words = preg_split('/\s+/', strip_tags($text));
        $minutes = max(1, ceil(count($words) / 130));
        return $minutes . ' min read';
    }

    /**
     * Truncate text cleanly
     */
    public static function truncateText($text, $limit = 140) {
        $clean = strip_tags($text);
        if (strlen($clean) <= $limit) {
            return $clean;
        }
        return substr($clean, 0, $limit) . '...';
    }

    /**
     * Render grid of news cards
     */
    public static function NewsByCategory($arr) {
        if (empty($arr)) {
            echo '<div class="alert alert-info" style="margin:20px 0;"><i class="fa fa-info-circle"></i> No published articles in this category yet.</div>';
            return;
        }

        echo '<div class="cp-news-grid">';
        foreach ($arr as $value) {
            $imgSrc = self::getImageSrc($value['picture']);
            $title = htmlspecialchars($value['title']);
            $text = self::truncateText($value['text'], 150);
            $catName = !empty($value['category_name']) ? htmlspecialchars($value['category_name']) : 'Technology';
            $readingTime = self::getReadingTime($value['text']);

            echo '<article class="cp-card">';
            echo '  <div class="cp-card-thumb">';
            echo '    <a href="news?id=' . $value['id'] . '">';
            echo '      <img src="' . $imgSrc . '" alt="' . $title . '" loading="lazy">';
            echo '    </a>';
            echo '    <span class="cp-badge-category">' . $catName . '</span>';
            echo '  </div>';
            echo '  <div class="cp-card-body">';
            echo '    <div class="cp-card-meta">';
            echo '      <span><i class="fa fa-clock-o"></i> ' . $readingTime . '</span>';
            echo '      <span><i class="fa fa-bolt" style="color:var(--cp-cyan);"></i> Trending</span>';
            echo '    </div>';
            echo '    <h3 class="cp-card-title"><a href="news?id=' . $value['id'] . '">' . $title . '</a></h3>';
            echo '    <p class="cp-card-text">' . $text . '</p>';
            echo '    <div class="cp-card-footer">';
            echo '      <div class="cp-comments-counter">';
            echo '        <i class="fa fa-comments-o"></i> ';
            Controller::CommentsCount($value['id']);
            echo '      </div>';
            echo '      <a href="news?id=' . $value['id'] . '" class="cp-read-more">Read Story <i class="fa fa-arrow-right"></i></a>';
            echo '    </div>';
            echo '  </div>';
            echo '</article>';
        }
        echo '</div>';
    }

    /**
     * Render all news (used in /all)
     */
    public static function AllNews($arr) {
        self::NewsByCategory($arr);
    }

    /**
     * Render single news article details
     */
    public static function ReadNews($n) {
        if (!$n) {
            echo '<div class="alert alert-warning">Article not found.</div>';
            return;
        }

        $imgSrc = self::getImageSrc($n['picture']);
        $title = htmlspecialchars($n['title']);
        $author = !empty($n['author_name']) ? htmlspecialchars($n['author_name']) : 'CyberPulse Editorial';
        $catName = !empty($n['category_name']) ? htmlspecialchars($n['category_name']) : 'Technology';
        $readingTime = self::getReadingTime($n['text']);
        $paragraphs = explode("\n\n", $n['text']);

        echo '<div class="cp-article-container">';
        echo '  <div class="cp-breadcrumbs">';
        echo '    <a href="./"><i class="fa fa-home"></i> Home</a> &raquo; ';
        echo '    <a href="category?id=' . $n['category_id'] . '">' . $catName . '</a> &raquo; ';
        echo '    <span>Article #' . $n['id'] . '</span>';
        echo '  </div>';

        echo '  <header class="cp-article-header">';
        echo '    <span class="cp-hero-pill"><i class="fa fa-tag"></i> ' . $catName . '</span>';
        echo '    <h1 class="cp-article-title">' . $title . '</h1>';
        echo '    <div class="cp-article-meta-bar">';
        echo '      <div><i class="fa fa-user-circle" style="color:var(--cp-cyan);"></i> By <strong>' . $author . '</strong></div>';
        echo '      <div><i class="fa fa-clock-o"></i> ' . $readingTime . '</div>';
        echo '      <div>';
        Controller::CommentsCountWithAncor($n['id']);
        echo '      </div>';
        echo '    </div>';
        echo '  </header>';

        echo '  <div class="cp-article-hero-img">';
        echo '    <img src="' . $imgSrc . '" alt="' . $title . '">';
        echo '  </div>';

        echo '  <div class="cp-article-body">';
        foreach ($paragraphs as $index => $para) {
            $trimmed = trim($para);
            if (!empty($trimmed)) {
                if ($index === 0) {
                    echo '<p style="font-size:18px; font-weight:500; color:#ffffff; line-height:1.7;">' . nl2br(htmlspecialchars($trimmed)) . '</p>';
                } else {
                    echo '<p>' . nl2br(htmlspecialchars($trimmed)) . '</p>';
                }
            }
        }
        echo '  </div>';
        echo '</div>';
    }
}
?>