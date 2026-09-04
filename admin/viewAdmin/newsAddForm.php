<?php ob_start(); ?>

<div class="cp-form-card">
    <h2>
        <i class="fa fa-plus-circle" style="color:var(--cp-cyan);"></i> Publish New Article
    </h2>

    <?php
    if (isset($test)) {
        if ($test == true) {
    ?>
            <div class="alert alert-info">
                <i class="fa fa-check-circle"></i> <strong>Article successfully published!</strong> 
                <a href="newsAdmin" class="cp-btn cp-btn-primary" style="margin-left:15px; padding:4px 12px; font-size:12px;">Back to Article List</a>
            </div>
    <?php
        } else if ($test == false) {
    ?>
            <div class="alert alert-warning">
                <i class="fa fa-exclamation-triangle"></i> <strong>Error saving publication!</strong> Please verify all required fields.
                <a href="newsAdmin" class="cp-btn cp-btn-outline" style="margin-left:15px; padding:4px 12px; font-size:12px;">Return to List</a>
            </div>
    <?php
        }
    } else {
    ?>
        <form method="POST" action="newsAddResult" enctype="multipart/form-data">
            <div style="margin-bottom:20px;">
                <label style="display:block; font-weight:600; margin-bottom:8px; color:#fff;">Article Title</label>
                <input type="text" name="title" class="form-control" placeholder="e.g. Breakthrough in Neuromorphic Photonic Silicon..." required autofocus>
            </div>

            <div style="margin-bottom:20px;">
                <label style="display:block; font-weight:600; margin-bottom:8px; color:#fff;">Topic / Category</label>
                <select name="idCategory" class="form-control" required>
                    <?php
                    foreach ($arr as $row) {
                        echo '<option value="' . $row['id'] . '">' . htmlspecialchars($row['name']) . '</option>';
                    }
                    ?>
                </select>
            </div>

            <div style="margin-bottom:20px;">
                <label style="display:block; font-weight:600; margin-bottom:8px; color:#fff;">Article Body (paragraphs supported)</label>
                <textarea rows="9" name="text" class="form-control" placeholder="Write full article body text..." required></textarea>
            </div>

            <div style="margin-bottom:25px;">
                <label style="display:block; font-weight:600; margin-bottom:8px; color:#fff;">Cover Illustration</label>
                <input type="file" name="picture" id="pictureInput" class="form-control" accept="image/*" onchange="previewImage(this)">
                <small style="color:var(--cp-text-dim); display:block; margin-top:6px;">Supports JPG, PNG, WebP, and SVG. If empty, a styled CyberPulse vector cover will be generated.</small>
                <div id="previewContainer" class="cp-preview-box" style="display:none; margin-top:12px;">
                    <img id="imagePreview" src="#" alt="Preview">
                </div>
            </div>

            <div style="display:flex; align-items:center; gap:12px; padding-top:15px; border-top:1px solid var(--cp-border);">
                <button type="submit" class="cp-btn cp-btn-primary" name="save">
                    <i class="fa fa-check"></i> Publish Article
                </button>
                <a href="newsAdmin" class="cp-btn cp-btn-outline">
                    <i class="fa fa-arrow-left"></i> Back to List
                </a>
            </div>
        </form>

        <script>
        function previewImage(input) {
            var container = document.getElementById('previewContainer');
            var preview = document.getElementById('imagePreview');
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