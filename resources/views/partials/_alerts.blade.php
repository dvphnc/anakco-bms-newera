@if($errors->any())
<div class="alert alert-error mb-4">
    <i class="fas fa-exclamation-circle alert-icon"></i>
    <div class="alert-message">
        <strong>Please fix the following errors:</strong>
        <ul class="alert-list">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

@if(session('success'))
<div class="alert alert-success mb-4">
    <i class="fas fa-check-circle alert-icon"></i>
    <div class="alert-message">{{ session('success') }}</div>
    <button class="alert-close" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-error mb-4">
    <i class="fas fa-exclamation-circle alert-icon"></i>
    <div class="alert-message">{{ session('error') }}</div>
    <button class="alert-close" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
</div>
@endif