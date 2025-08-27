<?php $__env->startSection('content'); ?>
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">Types Management</h4>
                            <p class="card-category">Manage material sub-types</p>
                        </div>
                        <div class="col-4 text-right">
                            <a href="<?php echo e(route('types.create')); ?>" class="btn btn-sm btn-primary">
                                <i class="fa fa-plus"></i> Add New Type
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo e(session('success')); ?>

                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table tablesorter" id="typesTable">
                            <thead class="text-primary">
                                <tr>
                                    <th>ID</th>
                                    <th>Type Name</th>
                                    <th>Material</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Jobs Count</th>
                                    <th>Created</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($type->id); ?></td>
                                    <td><strong><?php echo e($type->name); ?></strong></td>
                                    <td>
                                        <span class="badge badge-info">
                                            <?php echo e($type->material->name ?? 'Unknown'); ?>

                                        </span>
                                    </td>
                                    <td><?php echo e($type->description ?? '-'); ?></td>
                                    <td>
                                        <?php if($type->is_enabled): ?>
                                            <span class="badge badge-success">
                                                <i class="fas fa-check"></i> Enabled
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-warning">
                                                <i class="fas fa-pause"></i> Disabled
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge badge-secondary">
                                            <?php echo e($type->jobs->count()); ?>

                                        </span>
                                    </td>
                                    <td><?php echo e($type->created_at->format('M d, Y')); ?></td>
                                    <td class="text-right">
                                        <div class="dropdown">
                                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" 
                                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                <a class="dropdown-item" href="<?php echo e(route('types.edit', $type)); ?>">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                
                                                <form action="<?php echo e(route('types.toggle-status', $type)); ?>" method="POST" class="d-inline">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('PATCH'); ?>
                                                    <?php if($type->is_enabled): ?>
                                                        <button type="submit" class="dropdown-item text-warning">
                                                            <i class="fas fa-pause"></i> Disable
                                                        </button>
                                                    <?php else: ?>
                                                        <button type="submit" class="dropdown-item text-success">
                                                            <i class="fas fa-play"></i> Enable
                                                        </button>
                                                    <?php endif; ?>
                                                </form>
                                                
                                                <?php if($type->jobs->count() == 0): ?>
                                                    <div class="dropdown-divider"></div>
                                                    <form action="<?php echo e(route('types.destroy', $type)); ?>" method="POST" 
                                                          class="d-inline"
                                                          onsubmit="return confirm('Are you sure you want to delete this type?')">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="fas fa-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <div class="dropdown-divider"></div>
                                                    <span class="dropdown-item text-muted">
                                                        <i class="fas fa-lock"></i> Cannot delete (has jobs)
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('js'); ?>
<script>
    $(document).ready(function() {
        $('#typesTable').DataTable({
            "pageLength": 25,
            "order": [[ 2, "asc" ], [ 1, "asc" ]],
            "columnDefs": [
                { "orderable": false, "targets": 6 }
            ]
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', ['pageSlug' => 'Types Management'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Yazan\Desktop\sigma\staging\resources\views/admin/types/index.blade.php ENDPATH**/ ?>