@extends('layouts.admin')

@section('title', 'Plattformsinställningar')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-900">⚙️ Plattformsinställningar</h2>
        <p class="text-gray-600 mt-1">Konfigurera alla systeminställningar för din plattform</p>
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Brand & Logo -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b">
                <h3 class="text-xl font-semibold text-gray-900 flex items-center">
                    <span class="text-2xl mr-3">🎨</span>
                    Varumärke & Logotyp
                </h3>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Site Logo -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Webbplatslogotyp
                        </label>
                        @if(setting('site_logo'))
                            <div class="mb-3">
                                <img src="{{ Storage::url(setting('site_logo')) }}" alt="Current Logo" class="h-16 object-contain border rounded p-2">
                                <p class="text-xs text-gray-500 mt-1">Nuvarande logotyp</p>
                            </div>
                        @endif
                        <input type="file" name="site_logo" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <p class="text-xs text-gray-500 mt-1">Rekommenderad storlek: 200x60px (PNG med transparent bakgrund)</p>
                    </div>

                    <!-- Favicon -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Favicon
                        </label>
                        @if(setting('site_favicon'))
                            <div class="mb-3">
                                <img src="{{ Storage::url(setting('site_favicon')) }}" alt="Current Favicon" class="h-8 w-8 object-contain border rounded">
                                <p class="text-xs text-gray-500 mt-1">Nuvarande favicon</p>
                            </div>
                        @endif
                        <input type="file" name="site_favicon" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <p class="text-xs text-gray-500 mt-1">Rekommenderad storlek: 32x32px eller 64x64px</p>
                    </div>

                    <!-- Site Name -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Webbplatsnamn <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="site_name" value="{{ old('site_name', $settings['site_name']) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Site Tagline -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Tagline / Slogan
                        </label>
                        <input type="text" name="site_tagline" value="{{ old('site_tagline', $settings['site_tagline']) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                               placeholder="Professionella Tjänster i Hela Sverige">
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-green-50 to-teal-50 px-6 py-4 border-b">
                <h3 class="text-xl font-semibold text-gray-900 flex items-center">
                    <span class="text-2xl mr-3">📞</span>
                    Kontaktinformation
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Support E-post <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="site_email" value="{{ old('site_email', $settings['site_email']) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Support Telefon
                        </label>
                        <input type="text" name="site_phone" value="{{ old('site_phone', $settings['site_phone']) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Företagsadress
                        </label>
                        <input type="text" name="site_address" value="{{ old('site_address', $settings['site_address']) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    </div>
                </div>
            </div>
        </div>

        <!-- SEO Settings -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-yellow-50 to-orange-50 px-6 py-4 border-b">
                <h3 class="text-xl font-semibold text-gray-900 flex items-center">
                    <span class="text-2xl mr-3">🔍</span>
                    SEO-inställningar
                </h3>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            SEO Titel (Meta Title) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="seo_title" value="{{ old('seo_title', $settings['seo_title']) }}" 
                               maxlength="60" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500">
                        <p class="text-xs text-gray-500 mt-1">Max 60 tecken. Visas i sökmotorresultat och webbläsarens flik</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            SEO Beskrivning (Meta Description) <span class="text-red-500">*</span>
                        </label>
                        <textarea name="seo_description" rows="3" maxlength="160" required
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500">{{ old('seo_description', $settings['seo_description']) }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Max 160 tecken. Visas i sökmotorresultat</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            SEO Nyckelord (Keywords)
                        </label>
                        <input type="text" name="seo_keywords" value="{{ old('seo_keywords', $settings['seo_keywords']) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500"
                               placeholder="tjänster, boka, hemstädning, flyttstädning">
                        <p class="text-xs text-gray-500 mt-1">Separera nyckelord med komma</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Open Graph Bild (Social Media)
                        </label>
                        @if(setting('seo_og_image'))
                            <div class="mb-3">
                                <img src="{{ Storage::url(setting('seo_og_image')) }}" alt="Current OG Image" class="h-32 object-cover border rounded">
                                <p class="text-xs text-gray-500 mt-1">Nuvarande bild</p>
                            </div>
                        @endif
                        <input type="file" name="seo_og_image" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <p class="text-xs text-gray-500 mt-1">Rekommenderad storlek: 1200x630px. Visas när sidan delas på sociala medier</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loyalty Points Settings -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-6 py-4 border-b">
                <h3 class="text-xl font-semibold text-gray-900 flex items-center">
                    <span class="text-2xl mr-3">⭐</span>
                    Lojalitetspoäng
                </h3>
            </div>
            <div class="p-6 space-y-6">
                <!-- Enable/Disable -->
                <div class="flex items-center justify-between p-4 bg-purple-50 rounded-lg">
                    <div>
                        <label class="text-sm font-semibold text-gray-900">Aktivera Lojalitetspoäng</label>
                        <p class="text-xs text-gray-600 mt-1">Till åt användare att tjäna och lösa in poäng</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="loyalty_points_enabled" value="1" class="sr-only peer" 
                               {{ old('loyalty_points_enabled', $settings['loyalty_points_enabled']) ? 'checked' : '' }}>
                        <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-purple-600"></div>
                    </label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Points Value -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Poäng Värde (1 poäng = X kr) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="loyalty_points_value" 
                                   value="{{ old('loyalty_points_value', $settings['loyalty_points_value']) }}" 
                                   step="0.01" min="0.01" max="100" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                            <span class="absolute right-4 top-2.5 text-gray-500">kr</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Hur mycket 1 poäng är värt i kronor</p>
                    </div>

                    <!-- Earn Rate -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Intjäningsgrad (% av bokningsbelopp) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="loyalty_points_earn_rate" 
                                   value="{{ old('loyalty_points_earn_rate', $settings['loyalty_points_earn_rate']) }}" 
                                   step="0.1" min="0" max="100" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                            <span class="absolute right-4 top-2.5 text-gray-500">%</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Hur mycket av bokningsbeloppet som blir poäng</p>
                    </div>

                    <!-- Min Redeem -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Minsta Inlösen (poäng) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="loyalty_points_min_redeem" 
                               value="{{ old('loyalty_points_min_redeem', $settings['loyalty_points_min_redeem']) }}" 
                               min="1" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        <p class="text-xs text-gray-500 mt-1">Minsta antal poäng som kan lösas in</p>
                    </div>

                    <!-- Max Redeem Percent -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Max Inlösen (% av bokningsbelopp) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="loyalty_points_max_redeem_percent" 
                                   value="{{ old('loyalty_points_max_redeem_percent', $settings['loyalty_points_max_redeem_percent']) }}" 
                                   min="1" max="100" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                            <span class="absolute right-4 top-2.5 text-gray-500">%</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Max % av bokningsbelopp som kan betalas med poäng</p>
                    </div>

                    <!-- Expiry Days -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Poäng Giltighetstid (dagar) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="loyalty_points_expiry_days" 
                               value="{{ old('loyalty_points_expiry_days', $settings['loyalty_points_expiry_days']) }}" 
                               min="0" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        <p class="text-xs text-gray-500 mt-1">Hur många dagar poäng är giltiga (0 = aldrig utgår)</p>
                    </div>

                    <!-- New User Welcome Bonus -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Välkomstbonus (poäng) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="new_user_loyalty_bonus" 
                               value="{{ old('new_user_loyalty_bonus', $settings['new_user_loyalty_bonus']) }}" 
                               min="0" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        <p class="text-xs text-gray-500 mt-1">Antal poäng nya användare får vid registrering (0 = ingen bonus)</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking Settings -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-50 to-blue-50 px-6 py-4 border-b">
                <h3 class="text-xl font-semibold text-gray-900 flex items-center">
                    <span class="text-2xl mr-3">📋</span>
                    Bokningsinställningar
                </h3>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Avbokningstid (timmar före) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="booking_cancellation_hours" 
                               value="{{ old('booking_cancellation_hours', $settings['booking_cancellation_hours']) }}" 
                               min="0" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        <p class="text-xs text-gray-500 mt-1">Kunder kan avboka X timmar före bokad tid</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Standard Provisionsprocent <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="default_commission_rate" 
                                   value="{{ old('default_commission_rate', $settings['default_commission_rate']) }}" 
                                   step="0.1" min="0" max="100" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <span class="absolute right-4 top-2.5 text-gray-500">%</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Standard provision för nya företag</p>
                    </div>
                </div>

                <!-- Company Selection Features -->
                <div class="border-t pt-6 space-y-4">
                    <h4 class="font-semibold text-gray-900 text-lg">Företagsval på Bokningsformulär</h4>
                    
                    <!-- Show Companies -->
                    <div class="flex items-center p-4 bg-indigo-50 rounded-lg">
                        <input type="checkbox" name="booking_show_companies" value="1" 
                               {{ old('booking_show_companies', $settings['booking_show_companies']) ? 'checked' : '' }}
                               class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <div class="ml-3">
                            <label class="text-sm font-semibold text-gray-900">Visa Företagslista på Formulär</label>
                            <p class="text-xs text-gray-600">Visa tillgängliga företag efter att kunden valt stad och tjänst</p>
                        </div>
                    </div>

                    <!-- Allow Company Selection -->
                    <div class="flex items-center p-4 bg-blue-50 rounded-lg">
                        <input type="checkbox" name="booking_allow_company_selection" value="1" 
                               {{ old('booking_allow_company_selection', $settings['booking_allow_company_selection']) ? 'checked' : '' }}
                               class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <div class="ml-3">
                            <label class="text-sm font-semibold text-gray-900">Tillåt Kund Välja Företag</label>
                            <p class="text-xs text-gray-600">Låt kunden välja vilket företag de vill boka med (sorterat efter betyg)</p>
                        </div>
                    </div>

                    <!-- Auto Assign -->
                    <div class="flex items-center p-4 bg-green-50 rounded-lg border-2 border-green-300">
                        <input type="checkbox" name="booking_auto_assign" value="1" 
                               {{ old('booking_auto_assign', $settings['booking_auto_assign']) ? 'checked' : '' }}
                               class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500">
                        <div class="ml-3">
                            <label class="text-sm font-semibold text-gray-900">Automatisk Tilldelning (Auto-Assign)</label>
                            <p class="text-xs text-gray-600">
                                Om aktiverad: Bokning tilldelas automatiskt till valt företag eller bäst betygsatta företag.<br>
                                Om kunden bockar "Låt Bitra välja åt mig" används automatisk tilldelning till högst betygsatt företag.
                            </p>
                        </div>
                    </div>

                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                        <p class="text-sm font-semibold text-yellow-900 mb-2">⚠️ Viktigt om Företagsval:</p>
                        <ul class="text-sm text-yellow-800 space-y-1">
                            <li>• Om "Visa Företagslista" är aktiv, visas företag sorterade efter betyg</li>
                            <li>• Om "Tillåt Kund Välja Företag" är aktiv, kan kunden välja specifikt företag</li>
                            <li>• Kunden kan alltid välja "Låt Bitra välja åt mig" för automatiskt val</li>
                            <li>• Om "Automatisk Tilldelning" är aktiv, tilldelas bokningen direkt istället för "pending"</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.dashboard') }}" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                Avbryt
            </a>
            <button type="submit" class="px-8 py-3 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold rounded-lg transition shadow-lg">
                💾 Spara Alla Inställningar
            </button>
        </div>
    </form>
</div>
@endsection
