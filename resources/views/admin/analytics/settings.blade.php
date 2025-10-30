@extends('layouts.admin')

@section('title', 'Analytics Inställningar')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Analytics Inställningar</h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 col-lg-10">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Spårningsinställningar</h6>
                </div>
                <div class="card-body">
                    <form id="analyticsSettingsForm">
                        @csrf
                        
                        <!-- Google Analytics -->
                        <div class="form-group mb-4">
                            <label for="google_analytics_id" class="form-label">
                                <strong>Google Analytics ID</strong>
                            </label>
                            <input type="text" class="form-control" id="google_analytics_id" name="google_analytics_id" 
                                   value="{{ $settings['google_analytics_id'] ?? '' }}" 
                                   placeholder="G-XXXXXXXXXX">
                            <small class="form-text text-muted">
                                Ange ditt Google Analytics 4 Measurement ID
                            </small>
                        </div>

                        <!-- Google Search Console -->
                        <div class="form-group mb-4">
                            <label for="google_search_console" class="form-label">
                                <strong>Google Search Console</strong>
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="google_search_console" name="google_search_console" 
                                       value="{{ $settings['google_search_console'] ?? '' }}" 
                                       placeholder="Verifieringskod">
                                <button class="btn btn-outline-secondary" type="button" id="verifyGSC">
                                    Verifiera
                                </button>
                            </div>
                            <small class="form-text text-muted">
                                Verifieringskod för Google Search Console
                            </small>
                        </div>

                        <!-- Facebook Pixel -->
                        <div class="form-group mb-4">
                            <label for="facebook_pixel_id" class="form-label">
                                <strong>Facebook Pixel ID</strong>
                            </label>
                            <input type="text" class="form-control" id="facebook_pixel_id" name="facebook_pixel_id" 
                                   value="{{ $settings['facebook_pixel_id'] ?? '' }}" 
                                   placeholder="123456789012345">
                            <small class="form-text text-muted">
                                Ange ditt Facebook Pixel ID för spårning
                            </small>
                        </div>

                        <!-- Hotjar -->
                        <div class="form-group mb-4">
                            <label for="hotjar_id" class="form-label">
                                <strong>Hotjar Site ID</strong>
                            </label>
                            <input type="text" class="form-control" id="hotjar_id" name="hotjar_id" 
                                   value="{{ $settings['hotjar_id'] ?? '' }}" 
                                   placeholder="1234567">
                            <small class="form-text text-muted">
                                Ange ditt Hotjar Site ID för användarspårning
                            </small>
                        </div>

                        <!-- Tracking Options -->
                        <div class="form-group mb-4">
                            <label class="form-label"><strong>Spårningsalternativ</strong></label>
                            
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="track_page_views" name="track_page_views" 
                                       {{ ($settings['track_page_views'] ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="track_page_views">
                                    Spåra sidvisningar
                                </label>
                            </div>
                            
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="track_conversions" name="track_conversions" 
                                       {{ ($settings['track_conversions'] ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="track_conversions">
                                    Spåra konverteringar
                                </label>
                            </div>
                            
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="track_user_behavior" name="track_user_behavior" 
                                       {{ ($settings['track_user_behavior'] ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="track_user_behavior">
                                    Spåra användarbeteende (klick, scroll, tid på sida)
                                </label>
                            </div>
                            
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="track_geographic" name="track_geographic" 
                                       {{ ($settings['track_geographic'] ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="track_geographic">
                                    Spåra geografisk data
                                </label>
                            </div>
                            
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="track_seo_keywords" name="track_seo_keywords" 
                                       {{ ($settings['track_seo_keywords'] ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="track_seo_keywords">
                                    Spåra SEO nyckelord
                                </label>
                            </div>
                        </div>

                        <!-- Data Retention -->
                        <div class="form-group mb-4">
                            <label for="data_retention_days" class="form-label">
                                <strong>Datalagring (dagar)</strong>
                            </label>
                            <select class="form-control" id="data_retention_days" name="data_retention_days">
                                <option value="30" {{ ($settings['data_retention_days'] ?? 90) == 30 ? 'selected' : '' }}>30 dagar</option>
                                <option value="90" {{ ($settings['data_retention_days'] ?? 90) == 90 ? 'selected' : '' }}>90 dagar</option>
                                <option value="180" {{ ($settings['data_retention_days'] ?? 90) == 180 ? 'selected' : '' }}>180 dagar</option>
                                <option value="365" {{ ($settings['data_retention_days'] ?? 90) == 365 ? 'selected' : '' }}>1 år</option>
                            </select>
                            <small class="form-text text-muted">
                                Hur länge ska analytics data sparas
                            </small>
                        </div>

                        <!-- Privacy Settings -->
                        <div class="form-group mb-4">
                            <label class="form-label"><strong>Integritetsinställningar</strong></label>
                            
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="anonymize_ip" name="anonymize_ip" 
                                       {{ ($settings['anonymize_ip'] ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="anonymize_ip">
                                    Anonymisera IP-adresser
                                </label>
                            </div>
                            
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="respect_dnt" name="respect_dnt" 
                                       {{ ($settings['respect_dnt'] ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="respect_dnt">
                                    Respektera "Do Not Track" inställningar
                                </label>
                            </div>
                            
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="cookie_consent" name="cookie_consent" 
                                       {{ ($settings['cookie_consent'] ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="cookie_consent">
                                    Kräv cookie-samtycke för spårning
                                </label>
                            </div>
                        </div>

                        <!-- Alert Settings -->
                        <div class="form-group mb-4">
                            <label class="form-label"><strong>Varningsinställningar</strong></label>
                            
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="alert_low_conversion" name="alert_low_conversion" 
                                       {{ ($settings['alert_low_conversion'] ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="alert_low_conversion">
                                    Varna vid låg konverteringsgrad (&lt; 1%)
                                </label>
                            </div>
                            
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="alert_high_bounce" name="alert_high_bounce" 
                                       {{ ($settings['alert_high_bounce'] ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="alert_high_bounce">
                                    Varna vid hög studsgrad (&gt; 70%)
                                </label>
                            </div>
                            
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="alert_slow_pages" name="alert_slow_pages" 
                                       {{ ($settings['alert_slow_pages'] ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="alert_slow_pages">
                                    Varna vid långsamma sidor (&gt; 3s laddningstid)
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Spara inställningar
                            </button>
                            <button type="button" class="btn btn-secondary ml-2" id="testTracking">
                                <i class="fas fa-test-tube"></i> Testa spårning
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Integration Status -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Integrationsstatus</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="mr-3">
                                    <i class="fas fa-chart-line fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Google Analytics</h6>
                                    <span class="badge badge-{{ $settings['google_analytics_id'] ? 'success' : 'secondary' }}">
                                        {{ $settings['google_analytics_id'] ? 'Aktiverad' : 'Inaktiverad' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="mr-3">
                                    <i class="fab fa-facebook fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Facebook Pixel</h6>
                                    <span class="badge badge-{{ $settings['facebook_pixel_id'] ? 'success' : 'secondary' }}">
                                        {{ $settings['facebook_pixel_id'] ? 'Aktiverad' : 'Inaktiverad' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="mr-3">
                                    <i class="fas fa-search fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Google Search Console</h6>
                                    <span class="badge badge-{{ $settings['google_search_console'] ? 'success' : 'secondary' }}">
                                        {{ $settings['google_search_console'] ? 'Verifierad' : 'Overifierad' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="mr-3">
                                    <i class="fas fa-eye fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Hotjar</h6>
                                    <span class="badge badge-{{ $settings['hotjar_id'] ? 'success' : 'secondary' }}">
                                        {{ $settings['hotjar_id'] ? 'Aktiverad' : 'Inaktiverad' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success/Error Messages -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show position-fixed" style="top: 20px; right: 20px; z-index: 9999;">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show position-fixed" style="top: 20px; right: 20px; z-index: 9999;">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
@endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('analyticsSettingsForm');
    const testButton = document.getElementById('testTracking');
    
    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        
        fetch('/admin/analytics/settings', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Inställningar sparade!', 'success');
            } else {
                showAlert('Ett fel uppstod: ' + (data.message || 'Okänt fel'), 'error');
            }
        })
        .catch(error => {
            showAlert('Ett fel uppstod: ' + error.message, 'error');
        });
    });
    
    // Test tracking
    testButton.addEventListener('click', function() {
        fetch('/admin/analytics/test-tracking', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Spårningstest slutfört! Kontrollera analytics dashboard.', 'success');
            } else {
                showAlert('Test misslyckades: ' + (data.message || 'Okänt fel'), 'error');
            }
        })
        .catch(error => {
            showAlert('Test misslyckades: ' + error.message, 'error');
        });
    });
    
    function showAlert(message, type) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999;';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        `;
        
        document.body.appendChild(alertDiv);
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }
});
</script>
@endpush