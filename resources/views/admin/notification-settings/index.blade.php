@extends('layouts.admin')

@section('title', 'Notifieringsinställningar')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <span class="mr-3">🔔</span>
                    Notifieringsinställningar
                </h1>
                <p class="text-gray-600 mt-2">Hantera alla notifieringar och meddelanden i systemet</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.notification-settings.create') }}" 
                   class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    ➕ Skapa Ny
                </a>
            </div>
        </div>
    </div>

    <!-- Notification Categories -->
    @foreach($settings as $category => $categorySettings)
    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
        <div class="p-6 bg-gradient-to-r from-blue-50 to-purple-50 border-b">
            <h3 class="text-xl font-bold text-gray-900 flex items-center">
                <span class="text-2xl mr-3">
                    @switch($category)
                        @case('booking') 📋 @break
                        @case('user') 👤 @break
                        @case('company') 🏢 @break
                        @case('admin') ⚙️ @break
                        @case('system') 🔧 @break
                        @default 🔔
                    @endswitch
                </span>
                {{ ucfirst($category) }} Notifieringar
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Typ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ämne</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prioritet</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Åtgärder</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($categorySettings as $setting)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $setting->event }}</div>
                            <div class="text-xs text-gray-500">{{ $setting->category }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                @switch($setting->type)
                                    @case('email') bg-blue-100 text-blue-800 @break
                                    @case('sms') bg-green-100 text-green-800 @break
                                    @case('push') bg-purple-100 text-purple-800 @break
                                    @case('in_app') bg-orange-100 text-orange-800 @break
                                    @default bg-gray-100 text-gray-800
                                @endswitch">
                                {{ strtoupper($setting->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 max-w-xs truncate">
                                {{ $setting->subject ?: 'Inget ämne' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $setting->enabled ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $setting->enabled ? 'Aktiverad' : 'Inaktiverad' }}
                                </span>
                                @if($setting->auto_send)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Auto
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="text-lg {{ $i <= $setting->priority ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                                @endfor
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.notification-settings.edit', $setting) }}" 
                                   class="text-blue-600 hover:text-blue-900">Redigera</a>
                                
                                <form method="POST" action="{{ route('admin.notification-settings.toggle', $setting) }}" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="text-{{ $setting->enabled ? 'red' : 'green' }}-600 hover:text-{{ $setting->enabled ? 'red' : 'green' }}-900">
                                        {{ $setting->enabled ? 'Inaktivera' : 'Aktivera' }}
                                    </button>
                                </form>
                                
                                <form method="POST" action="{{ route('admin.notification-settings.destroy', $setting) }}" 
                                      class="inline" 
                                      onsubmit="return confirm('Är du säker på att du vill radera denna notifiering?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Radera</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endforeach

    <!-- Information Box -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-xl p-6">
        <div class="flex items-start">
            <div class="text-blue-500 text-2xl mr-3">ℹ️</div>
            <div>
                <h3 class="text-lg font-semibold text-blue-800">Notifieringsvariabler</h3>
                <div class="text-blue-700 mt-2 space-y-1">
                    <p>Du kan använda följande variabler i dina meddelanden:</p>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2 mt-3 text-sm">
                        <div><code>@{{user_name}}</code> - Användarens namn</div>
                        <div><code>@{{company_name}}</code> - Företagsnamn</div>
                        <div><code>@{{service_name}}</code> - Tjänstens namn</div>
                        <div><code>@{{booking_number}}</code> - Bokningsnummer</div>
                        <div><code>@{{booking_date}}</code> - Bokningsdatum</div>
                        <div><code>@{{total_amount}}</code> - Totalt belopp</div>
                        <div><code>@{{loyalty_points}}</code> - Lojalitetspoäng</div>
                        <div><code>@{{invoice_number}}</code> - Fakturanummer</div>
                        <div><code>@{{due_date}}</code> - Förfallodatum</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
