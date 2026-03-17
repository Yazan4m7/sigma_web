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
                       accept="image/png,image/jpeg,image/webp">
                <small class="form-text text-muted d-block mt-2">
                    Only PNG,jpeg,webp files are accepted. Maximum file size: 12MB.
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
                    <div class="text-center text-muted">
                        <i class="fas fa-user-circle fa-5x mb-2" style="opacity: 0.3;"></i>
                        <p class="mb-0">No image selected</p>
                    </div>
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

        if (!fileInput) {
            console.error('File input not found for ID: <?php echo $id; ?>');
            return;
        }

        const pickerContainer = fileInput.closest('.user-image-picker-container');

        if (!pickerContainer) {
            console.error('Picker container not found for input: <?php echo $id; ?>');
            return;
        }

        const previewContainer = pickerContainer.querySelector('.image-preview');
        const loadingIndicator = pickerContainer.querySelector('.loading-indicator');

        if (!previewContainer) {
            console.error('Preview container not found');
            return;
        }

        console.log('User image picker initialized:', {
            id: '<?php echo $id; ?>',
            hasPreview: !!previewContainer,
            hasLoading: !!loadingIndicator
        });

        fileInput.addEventListener('change', function(e) {
            console.log('File input changed', e.target.files);

            const file = this.files[0];
            if (!file) {
                console.log('No file selected');
                return;
            }

            console.log('File selected:', {
                name: file.name,
                size: file.size,
                type: file.type
            });

            if (file.size > 12 * 1024 * 1024) { // 12MB
                alert('File size must be less than 12MB');
                this.value = '';
                return;
            }

            // Hide preview, show loading
            if (previewContainer) {
                previewContainer.style.display = 'none';
            }
            if (loadingIndicator) {
                loadingIndicator.style.display = 'block';
            }

            const reader = new FileReader();

            reader.onerror = function(error) {
                console.error('FileReader error:', error);
                alert('Failed to read image file');
                if (loadingIndicator) loadingIndicator.style.display = 'none';
                if (previewContainer) previewContainer.style.display = 'block';
            };

            reader.onload = function(e) {
                console.log('FileReader loaded, showing preview');

                setTimeout(function() {
                    if (loadingIndicator) loadingIndicator.style.display = 'none';
                    if (previewContainer) previewContainer.style.display = 'block';

                    let img = previewContainer.querySelector('img');
                    if (!img) {
                        console.log('Creating new img element');
                        img = document.createElement('img');
                        img.className = 'img-fluid rounded';
                        img.style.maxHeight = '150px';
                        previewContainer.innerHTML = ''; // Clear any existing content
                        previewContainer.appendChild(img);
                    }

                    img.src = e.target.result;
                    img.alt = 'Selected profile image';

                    console.log('Preview image set');

                    const fallback = previewContainer.querySelector('span');
                    if (fallback) fallback.style.display = 'none';
                }, 500); // Reduced delay
            };

            reader.readAsDataURL(file);
        });
    });
</script>
