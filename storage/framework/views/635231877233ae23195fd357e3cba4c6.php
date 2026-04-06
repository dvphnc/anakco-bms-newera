<?php $__env->startSection('title', 'Register Resident'); ?>
<?php $__env->startSection('page-title', 'Residents'); ?>
<?php $__env->startSection('page-subtitle', 'Register a new resident'); ?>

<?php $__env->startSection('content'); ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Register Resident</h1>
        <p class="page-subtitle">Add a new resident to Barangay New Era</p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('residents.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>
</div>

<form method="POST" action="<?php echo e(route('residents.store')); ?>" enctype="multipart/form-data">
<?php echo csrf_field(); ?>


<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-user"></i> Personal Information</span>
    </div>
    <div class="card-body">

        <div class="form-section-title">Full Name</div>
        <div class="form-grid-3 mb-6">
            <div class="form-group">
                <label class="form-label">Last Name <span style="color:var(--crimson-mid)">*</span></label>
                <input type="text" name="last_name" class="form-control"
                       value="<?php echo e(old('last_name')); ?>" placeholder="e.g. Santos" required>
            </div>
            <div class="form-group">
                <label class="form-label">First Name <span style="color:var(--crimson-mid)">*</span></label>
                <input type="text" name="first_name" class="form-control"
                       value="<?php echo e(old('first_name')); ?>" placeholder="e.g. Juan" required>
            </div>
            <div class="form-group">
                <label class="form-label">Middle Name</label>
                <input type="text" name="middle_name" class="form-control"
                       value="<?php echo e(old('middle_name')); ?>" placeholder="e.g. Dela Cruz">
            </div>
        </div>

        <div class="form-section-title">Basic Information</div>
        <div class="form-grid-3 mb-6">
            <div class="form-group">
                <label class="form-label">Date of Birth <span style="color:var(--crimson-mid)">*</span></label>
                <input type="date" name="birthdate" class="form-control"
                       value="<?php echo e(old('birthdate')); ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Gender <span style="color:var(--crimson-mid)">*</span></label>
                <select name="gender" class="form-control" required>
                    <option value="">Select Gender</option>
                    <option value="Male"   <?php echo e(old('gender') === 'Male'   ? 'selected' : ''); ?>>Male</option>
                    <option value="Female" <?php echo e(old('gender') === 'Female' ? 'selected' : ''); ?>>Female</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Civil Status <span style="color:var(--crimson-mid)">*</span></label>
                <select name="civil_status" class="form-control" required>
                    <option value="">Select Status</option>
                    <?php $__currentLoopData = ['Single','Married','Widowed','Separated','Annulled']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s); ?>" <?php echo e(old('civil_status') === $s ? 'selected' : ''); ?>><?php echo e($s); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Nationality</label>
                <input type="text" name="nationality" class="form-control"
                       value="<?php echo e(old('nationality', 'Filipino')); ?>" placeholder="Filipino">
            </div>
            <div class="form-group">
                <label class="form-label">Religion</label>
                <input type="text" name="religion" class="form-control"
                       value="<?php echo e(old('religion')); ?>" placeholder="e.g. Roman Catholic">
            </div>
            <div class="form-group">
                <label class="form-label">Contact Number</label>
                <input type="text" name="contact_number" class="form-control"
                       value="<?php echo e(old('contact_number')); ?>" placeholder="09XX XXX XXXX">
            </div>
        </div>

        <div class="form-section-title">Address</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Purok <span style="color:var(--crimson-mid)">*</span></label>
                <select name="purok_id" class="form-control" required>
                    <option value="">Select Purok</option>
                    <?php $__currentLoopData = $puroks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $purok): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($purok->id); ?>" <?php echo e(old('purok_id') == $purok->id ? 'selected' : ''); ?>>
                            <?php echo e($purok->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Household</label>
                <select name="household_id" class="form-control">
                    <option value="">Select Household (optional)</option>
                    <?php $__currentLoopData = $households; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hh): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($hh->id); ?>" <?php echo e(old('household_id') == $hh->id ? 'selected' : ''); ?>>
                            <?php echo e($hh->household_number); ?> — <?php echo e($hh->household_head); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="form-group" style="grid-column:span 2">
                <label class="form-label">Full Address <span style="color:var(--crimson-mid)">*</span></label>
                <input type="text" name="address" class="form-control"
                       value="<?php echo e(old('address')); ?>"
                       placeholder="House No., Street, Barangay New Era, QC" required>
            </div>
        </div>

        <div class="form-section-title">Residency</div>
        <div class="form-grid-3 mb-6">
            <div class="form-group">
                <label class="form-label">Residency Status</label>
                <select name="residency_status" class="form-control">
                    <?php $__currentLoopData = ['Active','Deceased','Transferred']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s); ?>" <?php echo e(old('residency_status', 'Active') === $s ? 'selected' : ''); ?>><?php echo e($s); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Years of Residency</label>
                <input type="number" name="years_of_residency" class="form-control"
                       value="<?php echo e(old('years_of_residency')); ?>" min="0" placeholder="0">
            </div>
            <div class="form-group">
                <label class="form-label">Occupation</label>
                <input type="text" name="occupation" class="form-control"
                       value="<?php echo e(old('occupation')); ?>" placeholder="e.g. Teacher, Vendor">
            </div>
        </div>

    </div>
</div>


<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-tags"></i> Classifications</span>
    </div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px">
            <label class="form-check">
                <input type="checkbox" name="is_voter" value="1" <?php echo e(old('is_voter') ? 'checked' : ''); ?>>
                <span><strong>Registered Voter</strong></span>
            </label>
            <label class="form-check">
                <input type="checkbox" name="is_pwd" value="1" <?php echo e(old('is_pwd') ? 'checked' : ''); ?>>
                <span><strong>Person with Disability (PWD)</strong></span>
            </label>
            <label class="form-check">
                <input type="checkbox" name="is_senior" value="1" <?php echo e(old('is_senior') ? 'checked' : ''); ?>>
                <span><strong>Senior Citizen (60+)</strong></span>
            </label>
            <label class="form-check">
                <input type="checkbox" name="is_solo_parent" value="1" <?php echo e(old('is_solo_parent') ? 'checked' : ''); ?>>
                <span><strong>Solo Parent</strong></span>
            </label>
            <label class="form-check">
                <input type="checkbox" name="is_4ps" value="1" <?php echo e(old('is_4ps') ? 'checked' : ''); ?>>
                <span><strong>4Ps Beneficiary</strong></span>
            </label>
        </div>
    </div>
</div>


<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-camera"></i> Photo</span>
    </div>
    <div class="card-body">
        <div class="form-group" style="max-width:360px">
            <label class="form-label">Upload Photo (optional)</label>
            <input type="file" name="photo_path" class="form-control" accept="image/*">
            <div style="font-size:11px;color:var(--text-subtle);margin-top:4px">
                JPG, PNG or WEBP. Max 2MB.
            </div>
        </div>
    </div>
</div>


<div class="form-actions">
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-user-plus"></i> Register Resident
    </button>
    <a href="<?php echo e(route('residents.index')); ?>" class="btn btn-secondary">Cancel</a>
</div>

</form>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/residents/residents-create.blade.php ENDPATH**/ ?>