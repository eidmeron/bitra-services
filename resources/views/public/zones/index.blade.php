@extends('layouts.public')
@section('title', 'Alla Zoner')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-4xl font-bold mb-8">Alla Zoner</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($zones as $zone)
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-bold mb-2">{{ $zone->name }}</h3>
            <p class="text-gray-600">{{ $zone->cities_count }} städer</p>
        </div>
        @endforeach
    </div>
</div>
@endsection
