@extends('layouts.admin')

@section('title', 'Företag')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Företag</h2>
    <a href="{{ route('admin.companies.create') }}" class="btn btn-primary">
        + Lägg till företag
    </a>
</div>

<!-- Filters -->
<div class="card mb-6">
    <form method="GET" class="flex space-x-4">
        <input 
            type="text" 
            name="search" 
            placeholder="Sök företag, org.nr, e-post..." 
            value="{{ request('search') }}"
            class="form-input flex-1"
        >
        <select name="status" class="form-input" onchange="this.form.submit()">
            <option value="">Alla statusar</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktiv</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inaktiv</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Väntande</option>
        </select>
        <button type="submit" class="btn btn-primary">Sök</button>
    </form>
</div>

<!-- Companies Table -->
<div class="card">
    <div class="overflow-x-auto">
        <table class="table">
            <thead class="bg-gray-50">
                <tr>
                    <th>Företag</th>
                    <th>Kontakt</th>
                    <th>Org.nummer</th>
                    <th>Tjänster</th>
                    <th>Betyg</th>
                    <th>Status</th>
                    <th>Åtgärder</th>
                </tr>
            </thead>
            <tbody>
                @forelse($companies as $company)
                    <tr class="border-t hover:bg-gray-50">
                        <td>
                            <div class="flex items-center">
                                @if($company->company_logo)
                                    <img src="{{ Storage::url($company->company_logo) }}" alt="{{ $company->user->email }}" class="w-10 h-10 rounded mr-3">
                                @else
                                    <div class="w-10 h-10 bg-gray-200 rounded mr-3 flex items-center justify-center">
                                        <span class="text-gray-500 text-xl">🏢</span>
                                    </div>
                                @endif
                                <div>
                                    <div class="font-medium">{{ $company->user->email }}</div>
                                    @if($company->site)
                                        <a href="{{ $company->site }}" target="_blank" class="text-xs text-blue-600 hover:underline">
                                            {{ $company->site }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="text-sm">{{ $company->company_email }}</div>
                            <div class="text-xs text-gray-500">{{ $company->company_number }}</div>
                        </td>
                        <td class="font-mono text-sm">{{ $company->company_org_number }}</td>
                        <td>
                            <span class="text-sm text-gray-600">{{ $company->services->count() }} tjänster</span>
                        </td>
                        <td>
                            @if($company->review_count > 0)
                                <div class="flex items-center">
                                    <span class="font-semibold mr-1">{{ number_format($company->review_average, 1) }}</span>
                                    <span class="text-yellow-400">★</span>
                                    <span class="text-xs text-gray-500 ml-1">({{ $company->review_count }})</span>
                                </div>
                            @else
                                <span class="text-gray-400 text-sm">Inga betyg</span>
                            @endif
                        </td>
                        <td>{!! companyStatusBadge($company->status) !!}</td>
                        <td>
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.companies.show', $company) }}" class="text-blue-600 hover:underline text-sm">
                                    Visa
                                </a>
                                <a href="{{ route('admin.companies.edit', $company) }}" class="text-green-600 hover:underline text-sm">
                                    Redigera
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-8 text-gray-500">
                            Inga företag hittades
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $companies->links() }}
    </div>
</div>
@endsection

