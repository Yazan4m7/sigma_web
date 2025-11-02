<?php
/**
 * UserImagePicker Component (fixed)
 */
$id = 'user-image-picker-' . uniqid();
$current_image = $attributes['current_image'] ?? null;
?>

<div class="user-image-picker-container">
    <div class="row g-3">
        <!-- File Picker -->
        <div class="col-md-6">
            <label for="<?php echo $id; ?>" class="form-label fw-semibold mb-2">

            </label>
            <div class="p-3 border rounded bg-light">
                <input type="file"
                       class="form-control"
                       id="<?php echo $id; ?>"
                       name="photo"
                       accept=".png">
                <small class="form-text text-muted d-block mt-2">
                    Only PNG files are accepted. Maximum file size: 2MB.
                </small>
            </div>
        </div>

        <!-- Preview -->
        <div class="col-md-6 d-flex align-items-center justify-content-center">
            <div class="image-preview-container text-center">
                <div class="loading-indicator" style="display:none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p>Uploading image...</p>
                </div>

                <div class="image-preview">
                    <?php if ($current_image): ?>
                    <img src="<?php echo $current_image; ?>" alt="Profile image"
                         class="img-fluid rounded" style="max-height:150px;">
                    <?php else: ?>
                    <img src="/assets/images/default-avatar.png"
                         alt="Default profile image"
                         onerror="this.onerror=null; this.style.display='none'; document.getElementById('profile-fallback-<?php echo $id; ?>').style.display='inline';"
                         class="img-fluid rounded"
                         style="max-height:150px; display:inline;">
                    <span id="profile-fallback-<?php echo $id; ?>" style="display:none;">User's profile</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .image-preview-container {
        border: 1px dashed #ccc;
        border-radius: 6px;
        background-color: #f8f9fa;
        min-height: 160px;
        padding: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<style>
    .user-image-picker-container {
        margin-bottom: 2rem;
    }

    .input-container {
        background-color: #f8f9fa;
        border: 2px solid #c3c3c3;
        border-radius: 6px;
    }

    .user-image-input {
        cursor: pointer;
        height: auto; /* FIXED — no overlapping */
    }


    .bg-light{
        background-color: #ffffff !important;
    }
    .image-preview-container {
        border: 1px dashed #ccc;
        border-radius: 6px;
        background-color: #f8f9fa;
        min-height: 160px;
        padding: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .loading-indicator {
        text-align: center;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('<?php echo $id; ?>');
        const pickerContainer = fileInput.closest('.user-image-picker-container');
        const previewContainer = pickerContainer.querySelector('.image-preview');
        const loadingIndicator = pickerContainer.querySelector('.loading-indicator');

        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (!file) return;

            if (file.type !== 'image/png') {
                alert('Only PNG files are allowed');
                this.value = '';
                return;
            }

            if (file.size > 2 * 1024 * 1024) { // 2MB
                alert('File size must be less than 2MB');
                this.value = '';
                return;
            }

            previewContainer.style.display = 'none';
            loadingIndicator.style.display = 'block';

            const reader = new FileReader();
            reader.onload = function(e) {
                setTimeout(function() {
                    loadingIndicator.style.display = 'none';
                    previewContainer.style.display = 'block';

                    let img = previewContainer.querySelector('img');
                    if (!img) {
                        img = document.createElement('img');
                        img.className = 'img-fluid rounded';
                        img.style.maxHeight = '150px';
                        previewContainer.appendChild(img);
                    }

                    img.src = e.target.result;
                    img.alt = 'Selected profile image';
                    const fallback = previewContainer.querySelector('span');
                    if (fallback) fallback.style.display = 'none';
                }, 1000); // shorter delay
            };

            reader.readAsDataURL(file);
        });
    });
</script>
