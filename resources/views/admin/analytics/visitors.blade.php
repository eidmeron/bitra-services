@extends('layouts.admin')

@section('title', 'Visitor Analytics')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <span class="mr-3">👥</span>
                    Visitor Analytics
                </h1>
                <p class="text-gray-600 mt-2">Detailed visitor demographics and behavior analysis</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.analytics.index') }}" 
                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    📊 Overview
                </a>
                <a href="{{ route('admin.analytics.seo') }}" 
                   class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    🔍 SEO Analytics
                </a>
            </div>
        </div>
    </div>

    <!-- Period Filter -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <form method="GET" class="flex items-center space-x-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Period</label>
                <select name="period" class="border border-gray-300 rounded-lg px-3 py-2">
                    <option value="7" {{ request('period') == '7' ? 'selected' : '' }}>Last 7 days</option>
                    <option value="30" {{ request('period') == '30' ? 'selected' : '' }}>Last 30 days</option>
                    <option value="90" {{ request('period') == '90' ? 'selected' : '' }}>Last 90 days</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    Update
                </button>
            </div>
        </form>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Visitors -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-600 text-sm font-medium">Total Visitors</p>
                    <p class="text-3xl font-bold mt-2 text-gray-900">{{ number_format($demographics->sum('visitors')) }}</p>
                    <p class="text-xs text-gray-500 mt-1">Last {{ request('period', 30) }} days</p>
                </div>
                <div class="text-4xl opacity-75">👥</div>
            </div>
        </div>

        <!-- Total Sessions -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-600 text-sm font-medium">Total Sessions</p>
                    <p class="text-3xl font-bold mt-2 text-gray-900">{{ number_format($demographics->sum('sessions')) }}</p>
                    <p class="text-xs text-gray-500 mt-1">Last {{ request('period', 30) }} days</p>
                </div>
                <div class="text-4xl opacity-75">📊</div>
            </div>
        </div>

        <!-- Page Views -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-600 text-sm font-medium">Page Views</p>
                    <p class="text-3xl font-bold mt-2 text-gray-900">{{ number_format($demographics->sum('page_views')) }}</p>
                    <p class="text-xs text-gray-500 mt-1">Last {{ request('period', 30) }} days</p>
                </div>
                <div class="text-4xl opacity-75">📄</div>
            </div>
        </div>

        <!-- Avg Session Duration -->
        <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-600 text-sm font-medium">Avg Session</p>
                    <p class="text-3xl font-bold mt-2 text-gray-900">{{ number_format($demographics->avg('avg_session_duration'), 1) }}s</p>
                    <p class="text-xs text-gray-500 mt-1">Last {{ request('period', 30) }} days</p>
                </div>
                <div class="text-4xl opacity-75">⏱️</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Geographic Distribution -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-b">
                <h3 class="text-xl font-bold text-gray-900 flex items-center">
                    <span class="text-2xl mr-3">🌍</span>
                    Geographic Distribution
                </h3>
            </div>
            <div class="p-6">
                @if(isset($geographicDistribution) && $geographicDistribution->count() > 0)
                    <div class="space-y-4">
                        @foreach($geographicDistribution as $country => $data)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <span class="text-sm font-medium text-gray-900">{{ $country }}</span>
                            </div>
                            <div class="flex items-center space-x-4">
                                <span class="text-sm text-gray-600">{{ number_format($data['visitors']) }} visitors</span>
                                <div class="w-24 bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-500 h-2 rounded-full" style="width: {{ min(100, ($data['visitors'] / $geographicDistribution->max('visitors')) * 100) }}%"></div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="text-4xl mb-2">🌍</div>
                        <p class="text-gray-500">No geographic data available</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Device Analytics -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-green-50 to-emerald-50 border-b">
                <h3 class="text-xl font-bold text-gray-900 flex items-center">
                    <span class="text-2xl mr-3">📱</span>
                    Device Analytics
                </h3>
            </div>
            <div class="p-6">
                @if(isset($deviceAnalytics) && $deviceAnalytics->count() > 0)
                    <div class="space-y-4">
                        @foreach($deviceAnalytics as $device => $data)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <span class="text-sm font-medium text-gray-900">{{ ucfirst($device) }}</span>
                            </div>
                            <div class="flex items-center space-x-4">
                                <span class="text-sm text-gray-600">{{ number_format($data['visitors']) }} visitors</span>
                                <div class="w-24 bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ min(100, ($data['visitors'] / $deviceAnalytics->max('visitors')) * 100) }}%"></div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="text-4xl mb-2">📱</div>
                        <p class="text-gray-500">No device data available</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Browser Analytics -->
    <div class="mt-8 bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-6 bg-gradient-to-r from-purple-50 to-pink-50 border-b">
            <h3 class="text-xl font-bold text-gray-900 flex items-center">
                <span class="text-2xl mr-3">🌐</span>
                Browser Analytics
            </h3>
        </div>
        <div class="p-6">
            @if(isset($browserAnalytics) && $browserAnalytics->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($browserAnalytics as $browser => $data)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-900">{{ $browser }}</span>
                            <span class="text-sm text-gray-600">{{ number_format($data['visitors']) }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-purple-500 h-2 rounded-full" style="width: {{ min(100, ($data['visitors'] / $browserAnalytics->max('visitors')) * 100) }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <div class="text-4xl mb-2">🌐</div>
                    <p class="text-gray-500">No browser data available</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Information Box -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-xl p-6">
        <div class="flex items-start">
            <div class="text-blue-500 text-2xl mr-3">ℹ️</div>
            <div>
                <h3 class="text-lg font-semibold text-blue-800">Visitor Analytics</h3>
                <div class="text-blue-700 mt-2 space-y-1">
                    <p>• Data is collected automatically from visitor interactions</p>
                    <p>• Geographic data is based on IP address location</p>
                    <p>• Device and browser information is collected from user agents</p>
                    <p>• Session duration is calculated from page load times</p>
                    <p>• Data is updated in real-time as visitors browse the site</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
