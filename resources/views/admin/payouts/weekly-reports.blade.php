@extends('layouts.admin')

@section('title', 'Veckorapporter')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.payouts.index') }}">Utbetalningar</a></li>
                        <li class="breadcrumb-item active">Veckorapporter</li>
                    </ol>
                </div>
                <h4 class="page-title">Veckorapporter</h4>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Veckorapporter</h5>
                        <div class="btn-group">
                            <form method="POST" action="{{ route('admin.payouts.generate-weekly-reports') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-primary" onclick="return confirm('Generera veckorapporter för föregående vecka?')">
                                    <i class="ri-file-add-line"></i> Generera rapporter
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.payouts.send-weekly-reports') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success" onclick="return confirm('Skicka alla väntande veckorapporter?')">
                                    <i class="ri-mail-send-line"></i> Skicka rapporter
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Företag</label>
                            <select name="company_id" class="form-select">
                                <option value="">Alla företag</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                        {{ $company->company_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">Alla statusar</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Väntande</option>
                                <option value="sent" {{ request('status') === 'sent' ? 'selected' : '' }}>Skickade</option>
                                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Betalda</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Filtrera</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Reports Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Företag</th>
                                    <th>Vecka</th>
                                    <th>Period</th>
                                    <th>Bokningar</th>
                                    <th>Omsättning</th>
                                    <th>Provision</th>
                                    <th>ROT-avdrag</th>
                                    <th>Utbetalning</th>
                                    <th>Faktura</th>
                                    <th>Status</th>
                                    <th>Skickad</th>
                                    <th>Åtgärder</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reports as $report)
                                    <tr>
                                        <td>{{ $report->id }}</td>
                                        <td>
                                            <a href="{{ route('admin.companies.show', $report->company) }}" class="text-decoration-none">
                                                {{ $report->company->company_name }}
                                            </a>
                                        </td>
                                        <td>{{ $report->week_number }}, {{ $report->year }}</td>
                                        <td>
                                            {{ $report->period_start->format('M d') }} - {{ $report->period_end->format('M d') }}
                                        </td>
                                        <td>{{ $report->total_bookings }}</td>
                                        <td>{{ number_format($report->total_revenue, 0, ',', ' ') }} SEK</td>
                                        <td>{{ number_format($report->total_commission, 0, ',', ' ') }} SEK</td>
                                        <td>{{ number_format($report->total_rot_deduction, 0, ',', ' ') }} SEK</td>
                                        <td><strong>{{ number_format($report->net_payout, 0, ',', ' ') }} SEK</strong></td>
                                        <td>
                                            @if($report->invoice_number)
                                                <code>{{ $report->invoice_number }}</code>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @switch($report->status)
                                                @case('pending')
                                                    <span class="badge bg-warning">Väntande</span>
                                                    @break
                                                @case('sent')
                                                    <span class="badge bg-info">Skickad</span>
                                                    @break
                                                @case('paid')
                                                    <span class="badge bg-success">Betald</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td>
                                            @if($report->sent_at)
                                                {{ $report->sent_at->format('Y-m-d H:i') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.payouts.company-balance', $report->company) }}" class="btn btn-outline-primary">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                @if($report->status === 'pending')
                                                    <form method="POST" action="{{ route('admin.payouts.send-weekly-reports') }}" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="report_id" value="{{ $report->id }}">
                                                        <button type="submit" class="btn btn-outline-success" onclick="return confirm('Skicka denna rapport?')">
                                                            <i class="ri-mail-send-line"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="13" class="text-center text-muted">Inga veckorapporter hittades</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $reports->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
