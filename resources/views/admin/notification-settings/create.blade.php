@extends('layouts.admin')

@section('title', 'Create Notification Setting')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <span class="mr-3">➕</span>
                    Create Notification Setting
                </h1>
                <p class="text-gray-600 mt-2">Add a new notification setting to the system</p>
            </div>
            <a href="{{ route('admin.notification-settings.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                ← Back to Settings
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <form method="POST" action="{{ route('admin.notification-settings.store') }}">
            @csrf
            
            <div class="p-6 space-y-6">
                <!-- Basic Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                        <select name="type" id="type" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">Select Type</option>
                            <option value="email" {{ old('type') === 'email' ? 'selected' : '' }}>Email</option>
                            <option value="sms" {{ old('type') === 'sms' ? 'selected' : '' }}>SMS</option>
                            <option value="push" {{ old('type') === 'push' ? 'selected' : '' }}>Push Notification</option>
                            <option value="in_app" {{ old('type') === 'in_app' ? 'selected' : '' }}>In-App Notification</option>
                        </select>
                        @error('type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select name="category" id="category" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">Select Category</option>
                            <option value="booking" {{ old('category') === 'booking' ? 'selected' : '' }}>Booking</option>
                            <option value="user" {{ old('category') === 'user' ? 'selected' : '' }}>User</option>
                            <option value="company" {{ old('category') === 'company' ? 'selected' : '' }}>Company</option>
                            <option value="payment" {{ old('category') === 'payment' ? 'selected' : '' }}>Payment</option>
                            <option value="system" {{ old('category') === 'system' ? 'selected' : '' }}>System</option>
                        </select>
                        @error('category')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="event" class="block text-sm font-medium text-gray-700 mb-2">Event</label>
                    <input type="text" name="event" id="event" value="{{ old('event') }}" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                           placeholder="e.g., booking_created, user_registered" required>
                    @error('event')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                           placeholder="e.g., Booking Confirmation" required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" id="description" rows="3" 
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                              placeholder="Brief description of this notification">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Template -->
                <div id="email-template" class="hidden">
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Email Template</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                                <input type="text" name="subject" id="subject" value="{{ old('subject') }}" 
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                       placeholder="Email subject line">
                                @error('subject')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="body" class="block text-sm font-medium text-gray-700 mb-2">Body</label>
                                <textarea name="body" id="body" rows="8" 
                                          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                          placeholder="Email body content">{{ old('body') }}</textarea>
                                @error('body')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SMS Template -->
                <div id="sms-template" class="hidden">
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">SMS Template</h3>
                        
                        <div>
                            <label for="sms_body" class="block text-sm font-medium text-gray-700 mb-2">SMS Message</label>
                            <textarea name="sms_body" id="sms_body" rows="4" 
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                      placeholder="SMS message content (max 160 characters)">{{ old('sms_body') }}</textarea>
                            <p class="text-sm text-gray-500 mt-1">Character count: <span id="sms-count">0</span>/160</p>
                            @error('sms_body')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Settings -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Settings</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input type="checkbox" name="enabled" id="enabled" value="1" {{ old('enabled', true) ? 'checked' : '' }} 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="enabled" class="ml-2 block text-sm text-gray-900">Enabled</label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="auto_send" id="auto_send" value="1" {{ old('auto_send') ? 'checked' : '' }} 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="auto_send" class="ml-2 block text-sm text-gray-900">Auto Send</label>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label for="delay_minutes" class="block text-sm font-medium text-gray-700 mb-2">Delay (minutes)</label>
                                <input type="number" name="delay_minutes" id="delay_minutes" value="{{ old('delay_minutes', 0) }}" 
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                       min="0" max="1440">
                                @error('delay_minutes')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                                <select name="priority" id="priority" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="normal" {{ old('priority') === 'normal' ? 'selected' : '' }}>Normal</option>
                                    <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High</option>
                                </select>
                                @error('priority')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t flex items-center justify-between">
                <a href="{{ route('admin.notification-settings.index') }}" 
                   class="text-gray-600 hover:text-gray-800 font-medium">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                    Create Notification Setting
                </button>
            </div>
        </form>
    </div>

    <!-- Information Box -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-xl p-6">
        <div class="flex items-start">
            <div class="text-blue-500 text-2xl mr-3">ℹ️</div>
            <div>
                <h3 class="text-lg font-semibold text-blue-800">Notification Variables</h3>
                <div class="text-blue-700 mt-2 space-y-1">
                    <p>You can use the following variables in your templates:</p>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2 mt-3 text-sm">
                        <div><code>@{{user_name}}</code> - User's name</div>
                        <div><code>@{{company_name}}</code> - Company name</div>
                        <div><code>@{{service_name}}</code> - Service name</div>
                        <div><code>@{{booking_number}}</code> - Booking number</div>
                        <div><code>@{{booking_date}}</code> - Booking date</div>
                        <div><code>@{{total_amount}}</code> - Total amount</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const emailTemplate = document.getElementById('email-template');
    const smsTemplate = document.getElementById('sms-template');
    const smsBody = document.getElementById('sms-body');
    const smsCount = document.getElementById('sms-count');

    function toggleTemplates() {
        const type = typeSelect.value;
        
        // Hide all templates
        emailTemplate.classList.add('hidden');
        smsTemplate.classList.add('hidden');
        
        // Show relevant template
        if (type === 'email') {
            emailTemplate.classList.remove('hidden');
        } else if (type === 'sms') {
            smsTemplate.classList.remove('hidden');
        }
    }

    function updateSmsCount() {
        const count = smsBody.value.length;
        smsCount.textContent = count;
        
        if (count > 160) {
            smsCount.classList.add('text-red-500');
        } else {
            smsCount.classList.remove('text-red-500');
        }
    }

    typeSelect.addEventListener('change', toggleTemplates);
    smsBody.addEventListener('input', updateSmsCount);
    
    // Initialize
    toggleTemplates();
    updateSmsCount();
});
</script>
@endsection
