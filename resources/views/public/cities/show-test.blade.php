@extends('layouts.public')

@section('title', 'Test')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <h1>Test Page for {{ $city->name }}</h1>
    <p>This is a minimal test.</p>
</div>
@endsection

