<?php ob_start(); ?>

<div class="cp-form-card">
    <h2>
        <i class="fa fa-pencil" style="color:var(--cp-cyan);"></i> Edit Publication #<?php echo htmlspecialchars($id); ?>
    </h2>

    <?php
    if (isset($test)) {
        if ($test == true) {
    ?>
            <div class="alert alert-info">
                <i class="fa fa-check-circle"></i> <strong>Changes successfully saved!</strong>
                <a href="newsAdmin" class="cp-btn cp-btn-primary" style="margin-left:15px; padding:4px 12px; font-size:12px;">Back to Article List</a>
                <a href="../news?id=<?php echo htmlspecialchars($id); ?>" target="_blank" class="cp-btn cp-btn-outline" style="margin-left:8px; padding:4px 12px; font-size:12px;">View on Live Website</a>
            </div>
    <?php
        } else if ($test == false) {
    ?>
            <div class="alert alert-warning">
                <i class="fa fa-exclamation-triangle"></i> <strong>Error updating publication!</strong> Please verify the input data.
                <a href="newsAdmin" class="cp-btn cp-btn-outline" style="margin-left:15px; padding:4px 12px; font-size:12px;">Return to List</a>
            </div>
    <?php
        }
    } else {
        $imgSrc = ViewNews::getImageSrc($detail['picture']);
    ?>
        <form method="POST" action="newsEditResult?id=<?php echo $id; ?>" enctype="multipart/form-data">
            <div style="margin-bottom:20px;">
                <label style="display:block; font-weight:600; margin-bottom:8px; color:#fff;">Article Title</label>
                <input type="text" name="title" class="form-control" required value="<?php echo htmlspecialchars($detail['title']); ?>">
            </div>

            <div style="margin-bottom:20px;">
                <label style="display:block; font-weight:600; margin-bottom:8px; color:#fff;">Topic / Category</label>
                <select name="idCategory" class="form-control" required>
                    <?php
                    foreach ($arr as $row) {
                        $selected = ($row['id'] == $detail['category_id']) ? ' selected' : '';
                        echo '<option value="' . $row['id'] . '"' . $selected . '>' . htmlspecialchars($row['name']) . '</option>';
                    }
                    ?>
                </select>
            </div>

            <div style="margin-bottom:20px;">
                <label style="display:block; font-weight:600; margin-bottom:8px; color:#fff;">Article Body</label>
                <textarea rows="9" name="text" class="form-control" required><?php echo htmlspecialchars($detail['text']); ?></textarea>
            </div>

            <div style="margin-bottom:25px;">
                <label style="display:block; font-weight:600; margin-bottom:8px; color:#fff;">Current Cover</label>
                <div class="cp-preview-box">
                    <img src="<?php echo $imgSrc; ?>" alt="Current cover">
                </div>

                <div style="margin-top:15px;">
                    <label style="display:block; font-weight:600; margin-bottom:6px; color:#fff;">Replace Cover (optional)</label>
                    <input type="file" name="picture" id="editPictureInput" class="form-control" accept="image/*" onchange="previewEditImage(this)">
                    <small style="color:var(--cp-text-dim); display:block; margin-top:4px;">Leave empty to keep the existing cover image.</small>
                    <div id="editPreviewContainer" class="cp-preview-box" style="display:none; margin-top:10px;">
                        <img id="editImagePreview" src="#" alt="New Image Preview">
                    </div>
                </div>
            </div>

            <div style="display:flex; align-items:center; gap:12px; padding-top:15px; border-top:1px solid var(--cp-border);">
                <button type="submit" class="cp-btn cp-btn-primary" name="save">
                    <i class="fa fa-save"></i> Save Changes
                </button>
                <a href="newsAdmin" class="cp-btn cp-btn-outline">
                    <i class="fa fa-times"></i> Cancel
                </a>
            </div>
        </form>

        <script>
        function previewEditImage(input) {
            var container = document.getElementById('editPreviewContainer');
            var preview = document.getElementById('editImagePreview');
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    container.style.display = 'inline-block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        </script>
    <?php
    }
    ?>
</div>

<?php $content = ob_get_clean(); ?>
<?php include "viewAdmin/templates/layout.php"; ?>