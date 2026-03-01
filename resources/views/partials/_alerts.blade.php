{{-- Success Message --}}
@if(session('success'))
    <div class="alert alert-success" id="alert-success">
        <div class="alert-icon">
            <i class="fas fa-circle-check"></i>
        </div>
        <div class="alert-message">{{ session('success') }}</div>
        <button class="alert-close" onclick="this.parentElement.remove()">
            <i class="fas fa-xmark"></i>
        </button>
    </div>
@endif

{{-- Error Message --}}
@if(session('error'))
    <div class="alert alert-error" id="alert-error">
        <div class="alert-icon">
            <i class="fas fa-circle-xmark"></i>
        </div>
        <div class="alert-message">{{ session('error') }}</div>
        <button class="alert-close" onclick="this.parentElement.remove()">
            <i class="fas fa-xmark"></i>
        </button>
    </div>
@endif

{{-- Validation Errors --}}
@if($errors->any())
    <div class="alert alert-error" id="alert-validation">
        <div class="alert-icon">
            <i class="fas fa-triangle-exclamation"></i>
        </div>
        <div class="alert-message">
            <strong>Please fix the following errors:</strong>
            <ul class="alert-list">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button class="alert-close" onclick="this.parentElement.remove()">
            <i class="fas fa-xmark"></i>
        </button>
    </div>
@endif

{{-- Auto-dismiss alerts after 4 seconds --}}
<script>
    setTimeout(function () {
        ['alert-success', 'alert-error', 'alert-validation'].forEach(function (id) {
            const el = document.getElementById(id);
            if (el) {
                el.style.transition = 'opacity 0.5s';
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 500);
            }
        });
    }, 4000);
</script>