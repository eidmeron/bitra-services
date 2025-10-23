@extends('layouts.app')

@section('title', 'Mina Reklamationer')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                        <span class="text-3xl mr-3">📝</span>
                        Mina Reklamationer
                    </h1>
                    <a href="{{ route('user.bookings.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Se Mina Bokningar
                    </a>
                </div>

                @if($complaints->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Reklamation
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tjänst
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Prioritet
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Skapad
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Åtgärder
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($complaints as $complaint)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $complaint->subject }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ Str::limit($complaint->description, 50) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $complaint->booking->service->name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $complaint->booking->city->name }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $priorityColors = [
                                                    'low' => 'bg-gray-100 text-gray-800',
                                                    'medium' => 'bg-yellow-100 text-yellow-800',
                                                    'high' => 'bg-orange-100 text-orange-800',
                                                    'urgent' => 'bg-red-100 text-red-800',
                                                ];
                                                $priorityLabels = [
                                                    'low' => 'Låg',
                                                    'medium' => 'Medium',
                                                    'high' => 'Hög',
                                                    'urgent' => 'Akut',
                                                ];
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $priorityColors[$complaint->priority] }}">
                                                {{ $priorityLabels[$complaint->priority] }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusColors = [
                                                    'open' => 'bg-blue-100 text-blue-800',
                                                    'in_progress' => 'bg-yellow-100 text-yellow-800',
                                                    'resolved' => 'bg-green-100 text-green-800',
                                                    'closed' => 'bg-gray-100 text-gray-800',
                                                ];
                                                $statusLabels = [
                                                    'open' => 'Öppen',
                                                    'in_progress' => 'Pågående',
                                                    'resolved' => 'Löst',
                                                    'closed' => 'Stängd',
                                                ];
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$complaint->status] }}">
                                                {{ $statusLabels[$complaint->status] }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $complaint->created_at->format('Y-m-d H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('user.complaints.show', $complaint) }}" 
                                               class="text-blue-600 hover:text-blue-900 mr-3">
                                                Visa
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $complaints->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-6xl mb-4">📝</div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">
                            Inga reklamationer
                        </h3>
                        <p class="text-gray-500 mb-6">
                            Du har inte skapat några reklamationer än.
                        </p>
                        <a href="{{ route('user.bookings.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            Se Mina Bokningar
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
