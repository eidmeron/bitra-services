@extends('layouts.admin')

@section('title', 'E-postmallar')
@section('subtitle', 'Hantera e-postmallar för notifieringar')

@section('content')
<div class="container mx-auto p-6 bg-white rounded-xl shadow-lg">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                <span class="mr-3">📧</span>
                E-postmallar
            </h2>
            <p class="text-gray-600 mt-1">Hantera e-postmallar för alla typer av notifieringar</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.notification-settings.index') }}" 
               class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                🔔 Notifieringsinställningar
            </a>
        </div>
    </div>

    <!-- Templates by Category -->
    @foreach($templates as $category => $categoryTemplates)
        <div class="mb-8">
            <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                <span class="mr-2">
                    @switch($category)
                        @case('booking')
                            📅
                            @break
                        @case('company')
                            🏢
                            @break
                        @case('user')
                            👤
                            @break
                        @case('payment')
                            💳
                            @break
                        @case('admin')
                            👨‍💼
                            @break
                        @case('system')
                            ⚙️
                            @break
                        @default
                            📧
                    @endswitch
                </span>
                {{ ucfirst($category) }} Mallar
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($categoryTemplates as $template)
                    <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h4 class="text-lg font-semibold text-gray-900 mb-2">
                                        {{ ucfirst(str_replace('_', ' ', $template->event)) }}
                                    </h4>
                                    <p class="text-sm text-gray-600 mb-3">
                                        {{ $template->template ? Str::limit(strip_tags($template->template), 100) : 'Ingen mall definierad' }}
                                    </p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if($template->enabled)
                                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                            Aktiverad
                                        </span>
                                    @else
                                        <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">
                                            Inaktiverad
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm text-gray-600">
                                    <span class="font-medium mr-2">Typ:</span>
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs">
                                        {{ ucfirst($template->type) }}
                                    </span>
                                </div>
                                
                                @if($template->subject)
                                    <div class="flex items-center text-sm text-gray-600">
                                        <span class="font-medium mr-2">Ämne:</span>
                                        <span class="text-gray-900">{{ Str::limit($template->subject, 50) }}</span>
                                    </div>
                                @endif
                                
                                <div class="flex items-center text-sm text-gray-600">
                                    <span class="font-medium mr-2">Prioritet:</span>
                                    <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs">
                                        {{ $template->priority }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.email-templates.edit', $template) }}" 
                                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        ✏️ Redigera
                                    </a>
                                    <a href="{{ route('admin.email-templates.preview', $template) }}" 
                                       class="text-green-600 hover:text-green-800 text-sm font-medium">
                                        👁️ Förhandsvisning
                                    </a>
                                </div>
                                
                                @if($template->enabled)
                                    <form method="POST" action="{{ route('admin.notification-settings.toggle', $template) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                            🚫 Inaktivera
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.notification-settings.toggle', $template) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-800 text-sm font-medium">
                                            ✅ Aktivera
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    <!-- Information Box -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-xl p-6">
        <div class="flex items-start">
            <div class="text-blue-500 text-2xl mr-3">ℹ️</div>
            <div>
                <h3 class="text-lg font-semibold text-blue-800">Om E-postmallar</h3>
                <div class="text-blue-700 mt-2 space-y-1">
                    <p>• E-postmallar används för att skicka automatiska notifieringar till användare, företag och administratörer</p>
                    <p>• Mallarna stöder variabler som @{{user_name}}, @{{company_name}}, @{{booking_number}} etc.</p>
                    <p>• Du kan redigera mallarna, förhandsvisa dem och testa dem innan de skickas</p>
                    <p>• Aktivera/inaktivera mallar för att kontrollera vilka notifieringar som skickas</p>
                    <p>• Mallarna stöder HTML-formatering för professionella e-postmeddelanden</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
