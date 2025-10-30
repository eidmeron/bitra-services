@extends('layouts.admin')

@section('title', 'Redigera tjänst')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.services.index') }}" class="text-blue-600 hover:underline">&larr; Tillbaka till tjänster</a>
</div>

<div class="max-w-4xl mx-auto">
    <div class="card">
        <h2 class="text-2xl font-bold mb-6">Redigera: {{ $service->name }}</h2>

        <form method="POST" action="{{ route('admin.services.update', $service) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded mb-6">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Basic Information -->
            <div class="space-y-4 mb-6">
                <h3 class="text-lg font-semibold border-b pb-2">Grundinformation</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Tjänstnamn *</label>
                        <input type="text" name="name" value="{{ old('name', $service->name) }}" required class="form-input">
                    </div>

                    <div>
                        <label class="form-label">Kategori *</label>
                        <select name="category_id" required class="form-input">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $service->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Slug *</label>
                        <input type="text" name="slug" value="{{ old('slug', $service->slug) }}" required class="form-input">
                        <p class="text-xs text-gray-500 mt-1">Nuvarande: {{ $service->slug }}</p>
                    </div>

                    <div>
                        <label class="form-label">Status *</label>
                        <select name="status" required class="form-input">
                            <option value="active" {{ $service->status === 'active' ? 'selected' : '' }}>Aktiv</option>
                            <option value="inactive" {{ $service->status === 'inactive' ? 'selected' : '' }}>Inaktiv</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="form-label">Beskrivning</label>
                    <textarea name="description" rows="3" class="form-input">{{ old('description', $service->description) }}</textarea>
                </div>

                <div>
                    <label class="form-label">Icon (emoji)</label>
                    <input type="text" name="icon" value="{{ old('icon', $service->icon) }}" class="form-input" placeholder="🧹">
                </div>

                <div>
                    <label class="form-label">Bild</label>
                    @if($service->image)
                        <div class="mb-2">
                            <img src="{{ Storage::url($service->image) }}" alt="{{ $service->name }}" class="w-32 h-32 object-cover rounded">
                        </div>
                    @endif
                    <input type="file" name="image" accept="image/*" class="form-input">
                </div>
            </div>

            <!-- Pricing -->
            <div class="space-y-4 mb-6">
                <h3 class="text-lg font-semibold border-b pb-2">Prissättning</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Grundpris (kr) *</label>
                        <input type="number" name="base_price" value="{{ old('base_price', $service->base_price) }}" step="0.01" required class="form-input">
                    </div>

                    <div>
                        <label class="form-label">Rabatt (%)</label>
                        <input type="number" name="discount_percent" value="{{ old('discount_percent', $service->discount_percent) }}" step="0.01" min="0" max="100" class="form-input">
                    </div>

                    <div>
                        <label class="form-label">Moms (%)</label>
                        <input type="number" name="tax_rate" value="{{ old('tax_rate', $service->tax_rate) }}" step="0.01" min="0" max="100" class="form-input">
                        <p class="text-xs text-gray-500 mt-1">Standard: 25%</p>
                    </div>
                </div>
            </div>

            <!-- Booking Options -->
            <div class="space-y-4 mb-6">
                <h3 class="text-lg font-semibold border-b pb-2">Bokningstyper</h3>

                <div class="space-y-4">
                    <!-- One-time booking -->
                    <label class="flex items-center">
                        <input type="checkbox" name="one_time_booking" value="1" {{ old('one_time_booking', $service->one_time_booking) ? 'checked' : '' }} class="mr-2">
                        <span class="font-medium">📅 Engångsbokning</span>
                    </label>

                    <!-- Enable subscriptions -->
                    <label class="flex items-center">
                        <input type="checkbox" name="subscription_booking" value="1" {{ old('subscription_booking', $service->subscription_booking) ? 'checked' : '' }} class="mr-2" id="subscription_checkbox">
                        <span class="font-medium">🔄 Prenumerationsbokning</span>
                    </label>
                </div>
            </div>

            <!-- Subscription Types (show when subscription is enabled) -->
            <div class="space-y-4 mb-6" id="subscription_types_section" style="display: {{ old('subscription_booking', $service->subscription_booking) ? 'block' : 'none' }};">
                <h3 class="text-lg font-semibold border-b pb-2">Prenumerationstyper & Priser</h3>
                <p class="text-sm text-gray-600 mb-4">Välj vilka prenumerationstyper som ska vara tillgängliga och ange multiplikatorer (1.00 = inget rabatt, 0.90 = 10% rabatt, 1.05 = 5% påslag)</p>

                @php
                    $subscriptionTypes = old('subscription_types', $service->subscription_types ?? []);
                @endphp

                <!-- Daily -->
                <div class="border rounded-lg p-4">
                    <label class="flex items-center mb-3">
                        <input type="checkbox" name="subscription_types[]" value="daily" {{ in_array('daily', $subscriptionTypes) ? 'checked' : '' }} class="mr-2">
                        <span class="font-medium text-lg">⏰ Dagligen</span>
                    </label>
                    <div>
                        <label class="form-label text-sm">Multiplikator</label>
                        <input type="number" name="daily_multiplier" value="{{ old('daily_multiplier', $service->daily_multiplier) }}" step="0.01" min="0" max="2" class="form-input">
                        <p class="text-xs text-gray-500 mt-1">Standard: 1.05 (5% påslag - daglig service är mer krävande)</p>
                    </div>
                </div>

                <!-- Weekly -->
                <div class="border rounded-lg p-4">
                    <label class="flex items-center mb-3">
                        <input type="checkbox" name="subscription_types[]" value="weekly" {{ in_array('weekly', $subscriptionTypes) ? 'checked' : '' }} class="mr-2">
                        <span class="font-medium text-lg">📆 Veckovis</span>
                    </label>
                    <div>
                        <label class="form-label text-sm">Multiplikator</label>
                        <input type="number" name="weekly_multiplier" value="{{ old('weekly_multiplier', $service->weekly_multiplier) }}" step="0.01" min="0" max="2" class="form-input">
                        <p class="text-xs text-gray-500 mt-1">Standard: 1.00 (ordinarie pris)</p>
                    </div>
                </div>

                <!-- Bi-weekly -->
                <div class="border rounded-lg p-4">
                    <label class="flex items-center mb-3">
                        <input type="checkbox" name="subscription_types[]" value="biweekly" {{ in_array('biweekly', $subscriptionTypes) ? 'checked' : '' }} class="mr-2">
                        <span class="font-medium text-lg">📅 Varannan vecka</span>
                    </label>
                    <div>
                        <label class="form-label text-sm">Multiplikator</label>
                        <input type="number" name="biweekly_multiplier" value="{{ old('biweekly_multiplier', $service->biweekly_multiplier) }}" step="0.01" min="0" max="2" class="form-input">
                        <p class="text-xs text-gray-500 mt-1">Standard: 0.95 (5% rabatt)</p>
                    </div>
                </div>

                <!-- Monthly -->
                <div class="border rounded-lg p-4">
                    <label class="flex items-center mb-3">
                        <input type="checkbox" name="subscription_types[]" value="monthly" {{ in_array('monthly', $subscriptionTypes) ? 'checked' : '' }} class="mr-2">
                        <span class="font-medium text-lg">🗓️ Månadsvis</span>
                    </label>
                    <div>
                        <label class="form-label text-sm">Multiplikator</label>
                        <input type="number" name="monthly_multiplier" value="{{ old('monthly_multiplier', $service->monthly_multiplier) }}" step="0.01" min="0" max="2" class="form-input">
                        <p class="text-xs text-gray-500 mt-1">Standard: 0.90 (10% rabatt)</p>
                    </div>
                </div>
            </div>

            <!-- ROT-avdrag -->
            <div class="space-y-4 mb-6">
                <h3 class="text-lg font-semibold border-b pb-2">ROT-avdrag</h3>

                <div class="space-y-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="rot_eligible" value="1" {{ old('rot_eligible', $service->rot_eligible) ? 'checked' : '' }} class="mr-2">
                        <span>Berättigad till ROT-avdrag</span>
                    </label>

                    <div>
                        <label class="form-label">ROT-procent (%)</label>
                        <input type="number" name="rot_percent" value="{{ old('rot_percent', $service->rot_percent) }}" step="0.01" min="0" max="100" class="form-input">
                    </div>
                </div>
            </div>

            <!-- Content Management -->
            <div class="space-y-4 mb-6">
                <h3 class="text-lg font-semibold border-b pb-2">📝 Innehåll & Beskrivningar</h3>

                <!-- Full Content -->
                <div>
                    <label class="form-label">Fullständig Beskrivning</label>
                    <textarea name="full_content" rows="6" class="form-input" placeholder="Detaljerad beskrivning av tjänsten...">{{ old('full_content', $service->full_content) }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Detta visas under "Om Tjänsten" på tjänstesidan</p>
                </div>
            </div>

            <!-- What's Included -->
            <div class="space-y-4 mb-6" x-data="{ 
                includes: @json(old('includes', $service->includes ?? [])),
                addInclude() {
                    this.includes.push('');
                },
                removeInclude(index) {
                    this.includes.splice(index, 1);
                }
            }">
                <h3 class="text-lg font-semibold border-b pb-2 flex justify-between items-center">
                    <span>✅ Vad Ingår</span>
                    <button type="button" @click="addInclude()" class="btn btn-sm btn-primary">
                        + Lägg till
                    </button>
                </h3>

                <template x-for="(include, index) in includes" :key="index">
                    <div class="flex gap-2">
                        <input type="text" 
                               :name="'includes[' + index + ']'" 
                               x-model="includes[index]" 
                               class="form-input flex-1" 
                               placeholder="T.ex. Städning av alla rum">
                        <button type="button" @click="removeInclude(index)" class="btn btn-danger">
                            🗑️
                        </button>
                    </div>
                </template>

                <template x-if="includes.length === 0">
                    <p class="text-gray-500 text-sm italic">Inga inkluderingar tillagda. Klicka "+ Lägg till" för att lägga till.</p>
                </template>
            </div>

            <!-- Features -->
            <div class="space-y-4 mb-6" x-data="{ 
                features: @json(old('features', $service->features ?? [])),
                addFeature() {
                    this.features.push({ icon: '✨', title: '', description: '' });
                },
                removeFeature(index) {
                    this.features.splice(index, 1);
                }
            }">
                <h3 class="text-lg font-semibold border-b pb-2 flex justify-between items-center">
                    <span>⭐ Funktioner & Fördelar</span>
                    <button type="button" @click="addFeature()" class="btn btn-sm btn-primary">
                        + Lägg till Funktion
                    </button>
                </h3>

                <template x-for="(feature, index) in features" :key="index">
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <div class="flex justify-between items-start mb-3">
                            <span class="font-semibold text-gray-700">Funktion <span x-text="index + 1"></span></span>
                            <button type="button" @click="removeFeature(index)" class="text-red-600 hover:text-red-800">
                                🗑️ Ta bort
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div>
                                <label class="form-label text-sm">Ikon (emoji)</label>
                                <input type="text" 
                                       :name="'features[' + index + '][icon]'" 
                                       x-model="features[index].icon" 
                                       class="form-input" 
                                       placeholder="✨">
                            </div>
                            <div class="md:col-span-2">
                                <label class="form-label text-sm">Titel</label>
                                <input type="text" 
                                       :name="'features[' + index + '][title]'" 
                                       x-model="features[index].title" 
                                       class="form-input" 
                                       placeholder="T.ex. Professionell Utrustning">
                            </div>
                        </div>
                        <div class="mt-3">
                            <label class="form-label text-sm">Beskrivning</label>
                            <textarea 
                                :name="'features[' + index + '][description]'" 
                                x-model="features[index].description" 
                                rows="2" 
                                class="form-input" 
                                placeholder="Beskriv fördelen eller funktionen..."></textarea>
                        </div>
                    </div>
                </template>

                <template x-if="features.length === 0">
                    <p class="text-gray-500 text-sm italic">Inga funktioner tillagda. Klicka "+ Lägg till Funktion" för att lägga till.</p>
                </template>
            </div>

            <!-- FAQ -->
            <div class="space-y-4 mb-6" x-data="{ 
                faq: @json(old('faq', $service->faq ?? [])),
                addFaq() {
                    this.faq.push({ question: '', answer: '' });
                },
                removeFaq(index) {
                    this.faq.splice(index, 1);
                }
            }">
                <h3 class="text-lg font-semibold border-b pb-2 flex justify-between items-center">
                    <span>❓ Vanliga Frågor (FAQ)</span>
                    <button type="button" @click="addFaq()" class="btn btn-sm btn-primary">
                        + Lägg till Fråga
                    </button>
                </h3>

                <template x-for="(item, index) in faq" :key="index">
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <div class="flex justify-between items-start mb-3">
                            <span class="font-semibold text-gray-700">Fråga <span x-text="index + 1"></span></span>
                            <button type="button" @click="removeFaq(index)" class="text-red-600 hover:text-red-800">
                                🗑️ Ta bort
                            </button>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <label class="form-label text-sm">Fråga</label>
                                <input type="text" 
                                       :name="'faq[' + index + '][question]'" 
                                       x-model="faq[index].question" 
                                       class="form-input" 
                                       placeholder="T.ex. Hur lång tid tar städningen?">
                            </div>
                            <div>
                                <label class="form-label text-sm">Svar</label>
                                <textarea 
                                    :name="'faq[' + index + '][answer]'" 
                                    x-model="faq[index].answer" 
                                    rows="3" 
                                    class="form-input" 
                                    placeholder="Svaret på frågan..."></textarea>
                            </div>
                        </div>
                    </div>
                </template>

                <template x-if="faq.length === 0">
                    <p class="text-gray-500 text-sm italic">Inga frågor tillagda. Klicka "+ Lägg till Fråga" för att lägga till.</p>
                </template>
            </div>

            <!-- Cities -->
            <div class="space-y-4 mb-6">
                <h3 class="text-lg font-semibold border-b pb-2">Tillgängliga städer</h3>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                    @foreach($cities as $city)
                        <label class="flex items-center">
                            <input type="checkbox" name="cities[]" value="{{ $city->id }}" 
                                {{ in_array($city->id, old('cities', $service->cities->pluck('id')->toArray())) ? 'checked' : '' }} 
                                class="mr-2">
                            <span>{{ $city->name }} (×{{ $city->city_multiplier }})</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Statistics -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                <h4 class="font-semibold text-gray-900 mb-3">📊 Statistik</h4>
                <div class="grid grid-cols-3 gap-4 text-sm">
                    <div>
                        <p class="text-gray-600">Totala bokningar:</p>
                        <p class="font-semibold text-lg">{{ $service->bookings->count() }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Aktiva formulär:</p>
                        <p class="font-semibold text-lg">{{ $service->forms->where('status', 'active')->count() }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Företag:</p>
                        <p class="font-semibold text-lg">{{ $service->companies->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex justify-end space-x-4 pt-6 border-t">
                <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">
                    Avbryt
                </a>
                <button type="submit" class="btn btn-primary">
                    Uppdatera tjänst
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Show/hide subscription types when subscription checkbox is toggled
    document.getElementById('subscription_checkbox').addEventListener('change', function() {
        const typesSection = document.getElementById('subscription_types_section');
        typesSection.style.display = this.checked ? 'block' : 'none';
    });

    // Debug Alpine.js
    document.addEventListener('alpine:init', () => {
        console.log('Alpine.js initialized');
    });
</script>
@endpush

@endsection

