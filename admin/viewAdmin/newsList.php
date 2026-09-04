<?php ob_start(); ?>

<div class="cp-table-card">
    <div class="cp-table-header">
        <div>
            <h2 style="font-size:20px; font-weight:800; color:#fff; margin:0 0 4px;">
                <i class="fa fa-newspaper-o" style="color:var(--cp-cyan);"></i> All Published Articles
            </h2>
            <span style="font-size:12px; color:var(--cp-text-dim);">Total articles in database: <?php echo count($arr); ?></span>
        </div>
        <a class="cp-btn cp-btn-primary" href="newsAdd" role="button">
            <i class="fa fa-plus"></i> Add Article
        </a>
    </div>

    <div style="overflow-x:auto;">
        <table class="cp-admin-table">
            <thead>
                <tr>
                    <th style="width:50px;">ID</th>
                    <th style="width:90px;">Cover</th>
                    <th>Title & Topic</th>
                    <th style="width:140px;">Author</th>
                    <th style="width:160px; text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($arr)) {
                    foreach ($arr as $row) {
                        $imgSrc = ViewNews::getImageSrc($row['picture']);
                        $title = htmlspecialchars($row['title']);
                        $catName = htmlspecialchars($row['name'] ?? 'General');
                        $author = htmlspecialchars($row['username'] ?? 'CyberAdmin');

                        echo '<tr>';
                        echo '  <td style="font-family:var(--cp-font-mono); color:var(--cp-text-dim);">' . $row['id'] . '</td>';
                        echo '  <td>';
                        echo '    <img src="' . $imgSrc . '" alt="thumb" class="cp-table-thumb">';
                        echo '  </td>';
                        echo '  <td>';
                        echo '    <div style="font-weight:700; color:#fff; font-size:15px; margin-bottom:4px;">' . $title . '</div>';
                        echo '    <span style="background:rgba(0,240,255,0.1); color:var(--cp-cyan); font-size:11px; padding:2px 8px; border-radius:10px; font-weight:600;"><i class="fa fa-tag"></i> ' . $catName . '</span>';
                        echo '  </td>';
                        echo '  <td style="color:var(--cp-text-muted); font-size:13px;"><i class="fa fa-user-circle-o"></i> ' . $author . '</td>';
                        echo '  <td style="text-align:right;">';
                        echo '    <div style="display:inline-flex; gap:6px;">';
                        echo '      <a href="../news?id=' . $row['id'] . '" target="_blank" class="btn-action" style="background:rgba(255,255,255,0.05); color:#fff;" title="View on website"><i class="fa fa-eye"></i></a>';
                        echo '      <a href="newsEdit?id=' . $row['id'] . '" class="btn-action btn-action-edit" title="Edit article"><i class="fa fa-pencil"></i> Edit</a>';
                        echo '      <a href="newsDel?id=' . $row['id'] . '" class="btn-action btn-action-delete" title="Delete article"><i class="fa fa-trash"></i></a>';
                        echo '    </div>';
                        echo '  </td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="5" style="text-align:center; padding:30px; color:var(--cp-text-dim);">No publications found. Click "Add Article" to create one.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php include "viewAdmin/templates/layout.php"; ?>