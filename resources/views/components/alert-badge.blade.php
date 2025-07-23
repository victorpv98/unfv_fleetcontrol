<div class="alert {{ $getBootstrapClass() }} {{ $dismissible ? 'alert-dismissible' : '' }} fade show" 
     role="alert" 
     @if($autoHide) data-auto-hide="true" @endif>
    
    <i class="fas {{ $icon }} mr-2"></i>
    {{ $message }}
    
    @if($dismissible)
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alerts después de 5 segundos
    const autoHideAlerts = document.querySelectorAll('[data-auto-hide="true"]');
    autoHideAlerts.forEach(function(alert) {
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});
</script>