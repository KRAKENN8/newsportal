<?php
class ViewComments {
    /**
     * Comment submission form
     */
    public static function CommentsForm() {
        $newsId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        echo '<div class="cp-comment-form-container">';
        echo '  <h4 style="color:#ffffff; font-weight:700; margin-bottom:15px; display:flex; align-items:center; gap:8px;">';
        echo '    <i class="fa fa-pencil-square-o" style="color:var(--cp-cyan);"></i> Join the Discussion';
        echo '  </h4>';
        echo '  <form action="insertcomment" method="POST" class="cp-comment-form">';
        echo '    <input type="hidden" name="id" value="' . $newsId . '">';
        echo '    <textarea name="comment" placeholder="Share your perspective, benchmark insights, or feedback..." required></textarea>';
        echo '    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">';
        echo '      <span style="font-size:12px; color:var(--cp-text-dim);"><i class="fa fa-shield"></i> All comments are actively moderated</span>';
        echo '      <button type="submit" class="cp-btn cp-btn-primary">';
        echo '        <i class="fa fa-paper-plane"></i> Post Comment';
        echo '      </button>';
        echo '    </div>';
        echo '  </form>';
        echo '</div>';
    }

    /**
     * Render list of comments
     */
    public static function CommentsByNews($arr) {
        $count = $arr ? count($arr) : 0;
        echo '<section id="cp-comments" class="cp-comments-section">';
        echo '  <div class="cp-comments-header">';
        echo '    <h3 style="margin:0; font-size:20px; font-weight:700; color:#fff; display:flex; align-items:center; gap:10px;">';
        echo '      <i class="fa fa-comments" style="color:var(--cp-cyan);"></i> Discussion & Community Feedback';
        echo '      <span style="background:rgba(0, 240, 255, 0.15); color:var(--cp-cyan); padding:2px 10px; border-radius:12px; font-size:13px;">' . $count . '</span>';
        echo '    </h3>';
        echo '  </div>';

        if ($arr != null && count($arr) > 0) {
            echo '<div class="cp-comments-list" id="ctable">';
            foreach ($arr as $index => $value) {
                $author = !empty($value['username']) ? htmlspecialchars($value['username']) : 'User #' . ($index + 1);
                $initial = strtoupper(substr($author, 0, 1));
                $dateFormatted = date('M d, Y H:i', strtotime($value['date']));
                $text = htmlspecialchars($value['text']);

                echo '<div class="cp-comment-card">';
                echo '  <div class="cp-comment-avatar">' . $initial . '</div>';
                echo '  <div class="cp-comment-content">';
                echo '    <div class="cp-comment-meta">';
                echo '      <span class="cp-comment-author">' . $author . '</span>';
                echo '      <span class="cp-comment-date"><i class="fa fa-clock-o"></i> ' . $dateFormatted . '</span>';
                echo '    </div>';
                echo '    <p class="cp-comment-text">' . nl2br($text) . '</p>';
                echo '  </div>';
                echo '</div>';
            }
            echo '</div>';
        } else {
            echo '<div style="padding:20px; text-align:center; color:var(--cp-text-dim); background:rgba(255,255,255,0.01); border-radius:8px; margin-bottom:25px;" id="ctable">';
            echo '  <i class="fa fa-comment-o" style="font-size:28px; margin-bottom:8px; display:block; opacity:0.5;"></i>';
            echo '  No comments yet on this article. Be the first to share your thoughts!';
            echo '</div>';
        }
    }

    /**
     * Comment count with anchor for article page
     */
    public static function CommentsCountWithAncor($value) {
        $count = isset($value['count']) ? (int)$value['count'] : (is_numeric($value) ? (int)$value : 0);
        $label = ($count === 1) ? 'comment' : 'comments';
        echo '<a href="#cp-comments" style="color:var(--cp-cyan); font-weight:600; display:inline-flex; align-items:center; gap:5px;">';
        echo '  <i class="fa fa-comments-o"></i> ' . $count . ' ' . $label;
        echo '</a>';
    }

    /**
     * Comment count badge for card
     */
    public static function CommentsCount($value) {
        $count = isset($value['count']) ? (int)$value['count'] : (is_numeric($value) ? (int)$value : 0);
        echo '<span>' . $count . '</span>';
    }
}
?>