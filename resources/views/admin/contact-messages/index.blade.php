@extends('layouts.admin')

@section('title', 'Kontaktmeddelanden')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">📧 Kontaktmeddelanden</h1>
            <p class="text-gray-600 mt-2">Hantera meddelanden från kontaktformuläret</p>
        </div>
        @if($newCount > 0)
            <div class="bg-red-100 text-red-800 px-4 py-2 rounded-lg font-semibold">
                {{ $newCount }} nya meddelanden
            </div>
        @endif
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET" class="flex gap-4">
            <select name="status" class="px-4 py-2 border rounded-lg">
                <option value="">Alla status</option>
                <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>Nya</option>
                <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Lästa</option>
                <option value="replied" {{ request('status') === 'replied' ? 'selected' : '' }}>Besvarade</option>
            </select>
            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                Filtrera
            </button>
            <a href="{{ route('admin.contact-messages.index') }}" class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg">
                Rensa
            </a>
        </form>
    </div>

    @if($messages->isEmpty())
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <p class="text-gray-600">Inga meddelanden ännu.</p>
        </div>
    @else
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Namn</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">E-post</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Ämne</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Datum</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Åtgärder</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($messages as $message)
                        <tr class="hover:bg-gray-50 {{ $message->status === 'new' ? 'bg-blue-50' : '' }}">
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ 
                                    $message->status === 'new' ? 'bg-red-100 text-red-800' : 
                                    ($message->status === 'replied' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')
                                }}">
                                    {{ ucfirst($message->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $message->name }}
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $message->email }}
                            </td>
                            <td class="px-6 py-4 text-gray-900">
                                {{ Str::limit($message->subject, 40) }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 text-sm">
                                {{ $message->created_at->format('Y-m-d H:i') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.contact-messages.show', $message) }}" 
                                   class="text-blue-600 hover:text-blue-900 font-semibold mr-3">
                                    Visa
                                </a>
                                <form method="POST" action="{{ route('admin.contact-messages.destroy', $message) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            onclick="return confirm('Är du säker?')"
                                            class="text-red-600 hover:text-red-900 font-semibold">
                                        Radera
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $messages->links() }}
        </div>
    @endif
</div>
@endsection
