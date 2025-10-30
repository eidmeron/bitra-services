@extends('layouts.admin')

@section('title', 'Analytics Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Analytics Dashboard</h1>
                <div class="btn-group" role="group">
                    <input type="radio" class="btn-check" name="period" id="period-7d" value="7d" checked>
                    <label class="btn btn-outline-primary" for="period-7d">7 dagar</label>
                    
                    <input type="radio" class="btn-check" name="period" id="period-30d" value="30d">
                    <label class="btn btn-outline-primary" for="period-30d">30 dagar</label>
                    
                    <input type="radio" class="btn-check" name="period" id="period-90d" value="90d">
                    <label class="btn btn-outline-primary" for="period-90d">90 dagar</label>
                </div>
            </div>
        </div>
    </div>

    <!-- Overview Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Totala besökare</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-visitors">-</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Sidvisningar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-page-views">-</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-eye fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Konverteringar</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="conversions">-</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Konverteringsgrad</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="conversion-rate">-</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-percentage fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Traffic Sources Chart -->
        <div class="col-xl-6 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Trafikkällor</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="trafficSourcesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Device Breakdown Chart -->
        <div class="col-xl-6 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Enhetstyp</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="deviceBreakdownChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Tables Row -->
    <div class="row">
        <!-- Top Pages -->
        <div class="col-xl-6 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Mest besökta sidor</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="topPagesTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Sida</th>
                                    <th>Besök</th>
                                </tr>
                            </thead>
                            <tbody id="topPagesBody">
                                <!-- Data will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Geographic Distribution -->
        <div class="col-xl-6 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Geografisk fördelning</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="geographicTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Land/Stad</th>
                                    <th>Besök</th>
                                </tr>
                            </thead>
                            <tbody id="geographicBody">
                                <!-- Data will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SEO Keywords Row -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">SEO Nyckelord</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="seoKeywordsTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Nyckelord</th>
                                    <th>Sida</th>
                                    <th>Klick</th>
                                    <th>Visningar</th>
                                    <th>CTR</th>
                                    <th>Position</th>
                                </tr>
                            </thead>
                            <tbody id="seoKeywordsBody">
                                <!-- Data will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Insights Row -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Prestanda & SEO Analys</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="performanceTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Sida</th>
                                    <th>Laddningstid (ms)</th>
                                    <th>Studsgrad (%)</th>
                                    <th>Konverteringsgrad (%)</th>
                                    <th>SEO Poäng</th>
                                    <th>Problem</th>
                                </tr>
                            </thead>
                            <tbody id="performanceBody">
                                <!-- Data will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let trafficSourcesChart, deviceBreakdownChart;
    
    // Load initial data
    loadAnalyticsData('7d');
    
    // Period change handler
    document.querySelectorAll('input[name="period"]').forEach(radio => {
        radio.addEventListener('change', function() {
            loadAnalyticsData(this.value);
        });
    });
    
    function loadAnalyticsData(period) {
        fetch(`/admin/analytics/data?period=${period}`)
            .then(response => response.json())
            .then(data => {
                updateOverviewCards(data.overview);
                updateTrafficSourcesChart(data.traffic_sources);
                updateDeviceBreakdownChart(data.device_breakdown);
                updateTopPagesTable(data.top_pages);
                updateGeographicTable(data.geographic_data);
                updateSeoKeywordsTable(data.seo_keywords);
                updatePerformanceTable(data.performance_insights);
            })
            .catch(error => {
                console.error('Error loading analytics data:', error);
            });
    }
    
    function updateOverviewCards(overview) {
        document.getElementById('total-visitors').textContent = overview.total_visitors.toLocaleString();
        document.getElementById('total-page-views').textContent = overview.total_page_views.toLocaleString();
        document.getElementById('conversions').textContent = overview.conversions.toLocaleString();
        document.getElementById('conversion-rate').textContent = overview.conversion_rate.toFixed(2) + '%';
    }
    
    function updateTrafficSourcesChart(data) {
        const ctx = document.getElementById('trafficSourcesChart').getContext('2d');
        
        if (trafficSourcesChart) {
            trafficSourcesChart.destroy();
        }
        
        trafficSourcesChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: data.map(item => item.utm_source || 'Direkt'),
                datasets: [{
                    data: data.map(item => item.visits),
                    backgroundColor: [
                        '#4e73df',
                        '#1cc88a',
                        '#36b9cc',
                        '#f6c23e',
                        '#e74a3b'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
    
    function updateDeviceBreakdownChart(data) {
        const ctx = document.getElementById('deviceBreakdownChart').getContext('2d');
        
        if (deviceBreakdownChart) {
            deviceBreakdownChart.destroy();
        }
        
        deviceBreakdownChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: data.map(item => item.device_type),
                datasets: [{
                    data: data.map(item => item.visits),
                    backgroundColor: [
                        '#4e73df',
                        '#1cc88a',
                        '#36b9cc'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
    
    function updateTopPagesTable(data) {
        const tbody = document.getElementById('topPagesBody');
        tbody.innerHTML = '';
        
        data.forEach(item => {
            const row = tbody.insertRow();
            row.insertCell(0).textContent = item.page_title || item.page_url;
            row.insertCell(1).textContent = item.visits.toLocaleString();
        });
    }
    
    function updateGeographicTable(data) {
        const tbody = document.getElementById('geographicBody');
        tbody.innerHTML = '';
        
        data.forEach(item => {
            const row = tbody.insertRow();
            const location = item.city ? `${item.city}, ${item.country}` : item.country;
            row.insertCell(0).textContent = location;
            row.insertCell(1).textContent = item.visits.toLocaleString();
        });
    }
    
    function updateSeoKeywordsTable(data) {
        const tbody = document.getElementById('seoKeywordsBody');
        tbody.innerHTML = '';
        
        data.forEach(item => {
            const row = tbody.insertRow();
            row.insertCell(0).textContent = item.keyword;
            row.insertCell(1).textContent = item.page_url;
            row.insertCell(2).textContent = item.clicks.toLocaleString();
            row.insertCell(3).textContent = item.impressions.toLocaleString();
            row.insertCell(4).textContent = item.ctr.toFixed(2) + '%';
            row.insertCell(5).textContent = item.position || '-';
        });
    }
    
    function updatePerformanceTable(data) {
        const tbody = document.getElementById('performanceBody');
        tbody.innerHTML = '';
        
        data.forEach(item => {
            const row = tbody.insertRow();
            row.insertCell(0).textContent = item.page_title || item.page_url;
            row.insertCell(1).textContent = item.avg_load_time ? item.avg_load_time.toFixed(0) : '-';
            row.insertCell(2).textContent = item.bounce_rate ? item.bounce_rate.toFixed(2) + '%' : '-';
            row.insertCell(3).textContent = item.conversion_rate ? item.conversion_rate.toFixed(2) + '%' : '-';
            row.insertCell(4).textContent = item.seo_score || '-';
            row.insertCell(5).textContent = item.issues ? item.issues.join(', ') : '-';
        });
    }
});
</script>
@endpush