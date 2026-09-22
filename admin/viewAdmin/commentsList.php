<?php ob_start(); ?>

<div class="cp-table-card">
    <div class="cp-table-header">
        <div>
            <h2 style="font-size:20px; font-weight:800; color:#fff; margin:0 0 4px;">
                <i class="fa fa-comments" style="color:var(--cp-cyan);"></i> User Comments Moderation
            </h2>
            <span style="font-size:12px; color:var(--cp-text-dim);">Total comments in database: <?php echo count($comments); ?></span>
        </div>
    </div>

    <?php if (isset($_SESSION['adminFlash'])): ?>
        <div class="alert alert-info" style="margin: 15px 20px 0;">
            <i class="fa fa-check-circle"></i> <?php echo htmlspecialchars($_SESSION['adminFlash']); ?>
        </div>
        <?php unset($_SESSION['adminFlash']); ?>
    <?php endif; ?>

    <div style="overflow-x:auto;">
        <table class="cp-admin-table">
            <thead>
                <tr>
                    <th style="width:50px;">ID</th>
                    <th style="width:140px;">Author</th>
                    <th style="width:220px;">Article</th>
                    <th>Comment Text</th>
                    <th style="width:150px;">Date</th>
                    <th style="width:100px; text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($comments)) {
                    foreach ($comments as $row) {
                        $author = htmlspecialchars($row['username'] ?? 'User');
                        $newsTitle = htmlspecialchars($row['news_title'] ?? 'Deleted Story');
                        $newsId = (int)($row['news_id'] ?? 0);
                        $dateFormatted = date('M d, Y H:i', strtotime($row['date']));
                        $text = htmlspecialchars($row['text']);

                        echo '<tr>';
                        echo '  <td style="font-family:var(--cp-font-mono); color:var(--cp-text-dim);">' . $row['id'] . '</td>';
                        echo '  <td>';
                        echo '    <div style="font-weight:600; color:#fff; font-size:13px;"><i class="fa fa-user-circle-o" style="color:var(--cp-cyan);"></i> ' . $author . '</div>';
                        echo '  </td>';
                        echo '  <td>';
                        if ($newsId > 0) {
                            echo '    <a href="../news?id=' . $newsId . '" target="_blank" style="color:var(--cp-text-main); font-size:13px; text-decoration:none; display:block; max-width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="' . $newsTitle . '"><i class="fa fa-external-link"></i> ' . $newsTitle . '</a>';
                        } else {
                            echo '    <span style="color:var(--cp-text-dim); font-size:12px;">Unknown</span>';
                        }
                        echo '  </td>';
                        echo '  <td style="color:var(--cp-text-muted); font-size:13px; line-height:1.5; max-width:350px;">' . nl2br($text) . '</td>';
                        echo '  <td style="color:var(--cp-text-dim); font-size:12px; font-family:var(--cp-font-mono);">' . $dateFormatted . '</td>';
                        echo '  <td style="text-align:right;">';
                        echo '    <a href="commentDel?id=' . $row['id'] . '&csrf=' . Security::getCsrfToken() . '" class="btn-action btn-action-delete" data-confirm="Are you sure you want to permanently delete comment #' . $row['id'] . ' by ' . $author . '? This action cannot be undone." data-confirm-title="Confirm Comment Deletion" data-confirm-subtitle="ARE YOU SURE? // ВЫ УВЕРЕНЫ?" data-confirm-btn="Yes, Delete Comment" data-confirm-type="danger" title="Delete comment"><i class="fa fa-trash"></i> Delete</a>';
                        echo '  </td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="6" style="text-align:center; padding:30px; color:var(--cp-text-dim);">No user comments found in the database.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php include "viewAdmin/templates/layout.php"; ?>
