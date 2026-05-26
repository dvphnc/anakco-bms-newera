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
                <label class="form-label">Last Name <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="last_name" class="form-control <?php $__errorArgs = ['last_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       value="<?php echo e(old('last_name')); ?>" placeholder="e.g. Santos" required>
                <?php $__errorArgs = ['last_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> <?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="form-group">
                <label class="form-label">First Name <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="first_name" class="form-control <?php $__errorArgs = ['first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       value="<?php echo e(old('first_name')); ?>" placeholder="e.g. Juan" required>
                <?php $__errorArgs = ['first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> <?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="form-group">
                <label class="form-label">
                    Middle Name
                    <span class="help-icon" data-tippy-content="Enter the middle name as it appears on official IDs. Leave blank if the resident has none (e.g., illegitimate).">?</span>
                </label>
                <input type="text" name="middle_name" class="form-control <?php $__errorArgs = ['middle_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       value="<?php echo e(old('middle_name')); ?>" placeholder="e.g. Dela Cruz">
                <?php $__errorArgs = ['middle_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> <?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <div class="form-section-title">Basic Information</div>
        <div class="form-grid-3 mb-6">
            <div class="form-group">
                <label class="form-label">Date of Birth <span style="color:var(--crimson)">*</span></label>
                <input type="date" name="birthdate" class="form-control <?php $__errorArgs = ['birthdate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       value="<?php echo e(old('birthdate')); ?>" required>
                <?php $__errorArgs = ['birthdate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> <?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="form-group">
                <label class="form-label">Gender <span style="color:var(--crimson)">*</span></label>
                <select name="gender" id="s2Gender" class="form-control <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                    <option value="">Select Gender</option>
                    <option value="Male"   <?php echo e(old('gender') === 'Male'   ? 'selected' : ''); ?>>Male</option>
                    <option value="Female" <?php echo e(old('gender') === 'Female' ? 'selected' : ''); ?>>Female</option>
                </select>
                <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> <?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="form-group">
                <label class="form-label">
                    Civil Status <span style="color:var(--crimson)">*</span>
                    <span class="help-icon" data-tippy-content="Current marital status. 'Annulled' means a marriage was legally voided by court. This affects eligibility for some barangay programs.">?</span>
                </label>
                <select name="civil_status" id="s2CivilStatus" class="form-control <?php $__errorArgs = ['civil_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                    <option value="">Select Status</option>
                    <?php $__currentLoopData = ['Single','Married','Widowed','Separated','Annulled']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s); ?>" <?php echo e(old('civil_status') === $s ? 'selected' : ''); ?>><?php echo e($s); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['civil_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> <?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="form-group">
                <label class="form-label">Nationality</label>
                <input type="text" name="nationality" class="form-control <?php $__errorArgs = ['nationality'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       value="<?php echo e(old('nationality', 'Filipino')); ?>" placeholder="Filipino">
                <?php $__errorArgs = ['nationality'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> <?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="form-group">
                <label class="form-label">Religion</label>
                <input type="text" name="religion" class="form-control <?php $__errorArgs = ['religion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       value="<?php echo e(old('religion')); ?>" placeholder="e.g. Roman Catholic">
                <?php $__errorArgs = ['religion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> <?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="form-group">
                <label class="form-label">Contact Number</label>
                <input type="text" name="contact_number" class="form-control <?php $__errorArgs = ['contact_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       value="<?php echo e(old('contact_number')); ?>" placeholder="09XX XXX XXXX">
                <?php $__errorArgs = ['contact_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> <?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email_address" class="form-control <?php $__errorArgs = ['email_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       value="<?php echo e(old('email_address')); ?>" placeholder="e.g. juan@email.com">
                <?php $__errorArgs = ['email_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> <?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <div class="form-section-title">Address</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">
                    Purok <span style="color:var(--crimson)">*</span>
                    <span class="help-icon" data-tippy-content="Select the zone or neighborhood (Purok) where this resident currently lives. Contact the Barangay Secretary if you are unsure which Purok applies.">?</span>
                </label>
                <select name="purok_id" id="s2Purok" class="form-control <?php $__errorArgs = ['purok_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                    <option value="">Select Purok</option>
                    <?php $__currentLoopData = $puroks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $purok): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($purok->id); ?>" <?php echo e(old('purok_id') == $purok->id ? 'selected' : ''); ?>>
                            <?php echo e($purok->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['purok_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> <?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="form-group">
                <label class="form-label">
                    Household
                    <span class="help-icon" data-tippy-content="Optional. Link this resident to a registered household. This automatically updates the household's family size count. You can skip this and add it later.">?</span>
                </label>
                <select name="household_id" id="s2Household" class="form-control <?php $__errorArgs = ['household_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <option value="">Select Household (optional)</option>
                    <?php $__currentLoopData = $households; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hh): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($hh->id); ?>" <?php echo e(old('household_id') == $hh->id ? 'selected' : ''); ?>>
                            <?php echo e($hh->household_number); ?> — <?php echo e($hh->household_head); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['household_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> <?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="form-group" style="grid-column:span 2">
                <label class="form-label">Full Address <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="address" class="form-control <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       value="<?php echo e(old('address')); ?>"
                       placeholder="House No., Street, Barangay New Era, QC" required>
                <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> <?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <div class="form-section-title">Residency</div>
        <div class="form-grid-3 mb-6">
            <div class="form-group">
                <label class="form-label">
                    Residency Status
                    <span class="help-icon" data-tippy-content="'Active' = currently living here. 'Transferred' = moved to another address. 'Deceased' = passed away. Only Active residents appear in document requests.">?</span>
                </label>
                <select name="residency_status" id="s2ResidencyStatus" class="form-control <?php $__errorArgs = ['residency_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__currentLoopData = ['Active','Deceased','Transferred']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s); ?>" <?php echo e(old('residency_status', 'Active') === $s ? 'selected' : ''); ?>><?php echo e($s); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['residency_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> <?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="form-group">
                <label class="form-label">
                    Years of Residency
                    <span class="help-icon" data-tippy-content="How many years this person has been continuously living in Barangay New Era. Used for residency certificates. Enter 0 if newly arrived.">?</span>
                </label>
                <input type="number" name="years_of_residency" class="form-control <?php $__errorArgs = ['years_of_residency'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       value="<?php echo e(old('years_of_residency')); ?>" min="0" placeholder="0">
                <?php $__errorArgs = ['years_of_residency'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> <?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="form-group">
                <label class="form-label">Occupation</label>
                <input type="text" name="occupation" class="form-control <?php $__errorArgs = ['occupation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       value="<?php echo e(old('occupation')); ?>" placeholder="e.g. Teacher, Vendor">
                <?php $__errorArgs = ['occupation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> <?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

    </div>
</div>


<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-tags"></i> Classifications</span>
        <span class="help-icon" data-tippy-content="Check all that apply. Classifications help generate targeted reports and identify eligible residents for government programs like OSCA, PWD, and 4Ps benefits." style="font-size:13px;width:22px;height:22px">?</span>
    </div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:18px">
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
            <label class="form-label">Upload Photo <span style="color:#9ca3af;font-weight:400">(optional)</span></label>
            
            <label for="photoFileInput" id="photoDropZone" style="display:block;border:2px dashed var(--border);border-radius:var(--radius);padding:20px;text-align:center;cursor:pointer;transition:border-color .2s,background .2s">
                <i class="fas fa-camera" style="font-size:26px;color:var(--text-muted);margin-bottom:6px;display:block"></i>
                <div style="font-size:13px;color:var(--text-muted);margin-bottom:3px">Drag & drop or <span style="color:var(--navy);font-weight:600">browse</span></div>
                <div style="font-size:12px;color:var(--text-subtle)">JPG, PNG, WEBP — max 2MB</div>
            </label>
            <input type="file" id="photoFileInput" name="photo_path" accept="image/*" style="display:none">
            
            <div id="photoSelectedCard" style="display:none;border:2px solid var(--navy);border-radius:var(--radius);padding:12px 16px;align-items:center;gap:14px">
                <img id="photoDropPreview" src="" alt="" style="width:56px;height:56px;border-radius:50%;object-fit:cover;flex-shrink:0;border:2px solid var(--border)">
                <div style="flex:1;min-width:0">
                    <div id="photoDropName" style="font-size:13px;font-weight:600;color:var(--navy);white-space:nowrap;overflow:hidden;text-overflow:ellipsis"></div>
                    <div style="font-size:11px;color:var(--text-subtle);margin-top:2px">Ready to upload</div>
                </div>
                <button type="button" onclick="clearResidentPhoto()" style="flex-shrink:0;background:none;border:1px solid var(--danger,#e53e3e);border-radius:var(--radius-sm,6px);color:var(--danger,#c0392b);font-size:12px;cursor:pointer;padding:5px 12px;white-space:nowrap"><i class="fas fa-times"></i> Remove</button>
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

<?php $__env->startPush('scripts'); ?>
<script>
$(function () {
    const s2 = { dropdownParent: $('body'), width: '100%' };

    $('#s2Gender').select2($.extend({}, s2, { placeholder: 'Select Gender', allowClear: false }));
    $('#s2CivilStatus').select2($.extend({}, s2, { placeholder: 'Select Status', allowClear: false }));
    $('#s2ResidencyStatus').select2($.extend({}, s2, { placeholder: 'Select Status', allowClear: false }));

    /* Long lists — searchable */
    $('#s2Purok').select2($.extend({}, s2, {
        placeholder: 'Select Purok',
        allowClear: true
    }));
    $('#s2Household').select2($.extend({}, s2, {
        placeholder: 'Select Household (optional)',
        allowClear: true
    }));
});

/* ── Photo drag-and-drop ── */
(function () {
    var zone  = document.getElementById('photoDropZone');
    var input = document.getElementById('photoFileInput');
    if (!zone || !input) return;

    /* click-to-browse is handled natively by <label for="photoFileInput"> — no JS click handler needed */

    zone.addEventListener('dragover', function (e) {
        e.preventDefault(); e.stopPropagation();
        zone.style.borderColor     = 'var(--navy)';
        zone.style.backgroundColor = 'rgba(13,33,68,.04)';
    });
    zone.addEventListener('dragleave', function (e) {
        e.preventDefault(); e.stopPropagation();
        zone.style.borderColor     = '';
        zone.style.backgroundColor = '';
    });
    zone.addEventListener('drop', function (e) {
        e.preventDefault(); e.stopPropagation();
        zone.style.borderColor     = '';
        zone.style.backgroundColor = '';
        var files = e.dataTransfer.files;
        if (files.length) { var dt = new DataTransfer(); dt.items.add(files[0]); input.files = dt.files; showResidentPhoto(files[0]); }
    });
    input.addEventListener('change', function () { if (input.files[0]) showResidentPhoto(input.files[0]); });

    function showResidentPhoto(file) {
        var reader = new FileReader();
        reader.onload = function (ev) {
            document.getElementById('photoDropPreview').src = ev.target.result;
            document.getElementById('photoDropName').textContent = file.name;
            document.getElementById('photoDropZone').style.display = 'none';
            document.getElementById('photoSelectedCard').style.display = 'flex';
        };
        reader.readAsDataURL(file);
    }
}());
function clearResidentPhoto() {
    document.getElementById('photoFileInput').value = '';
    document.getElementById('photoDropPreview').src = '';
    document.getElementById('photoDropZone').style.display = 'block';
    document.getElementById('photoSelectedCard').style.display = 'none';
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views\residents\residents-create.blade.php ENDPATH**/ ?>