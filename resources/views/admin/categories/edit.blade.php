@extends('layouts.admin')

@section('title', 'Redigera kategori')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.categories.index') }}" class="text-blue-600 hover:underline">&larr; Tillbaka till kategorier</a>
</div>

<div class="max-w-2xl mx-auto">
    <div class="card">
        <h2 class="text-2xl font-bold mb-6">Redigera: {{ $category->name }}</h2>

        <form method="POST" action="{{ route('admin.categories.update', $category) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded mb-6">
                    <h4 class="font-semibold mb-2">Valideringsfel:</h4>
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded mb-6">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded mb-6">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <!-- Debug Info (remove in production) -->
            @if(config('app.debug'))
                <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded mb-6">
                    <h4 class="font-semibold mb-2">Debug Info:</h4>
                    <p><strong>Category ID:</strong> {{ $category->id }}</p>
                    <p><strong>Current Name:</strong> {{ $category->name }}</p>
                    <p><strong>Current Status:</strong> {{ $category->status }}</p>
                    <p><strong>Services Count:</strong> {{ $category->services_count }}</p>
                </div>
            @endif

            <div class="space-y-6">
                <div>
                    <label class="form-label">Kategorinamn *</label>
                    <input type="text" name="name" value="{{ old('name', $category->name) }}" required class="form-input">
                </div>

                <div>
                    <label class="form-label">Slug</label>
                    <input type="text" value="{{ $category->slug }}" disabled class="form-input bg-gray-100">
                    <p class="text-xs text-gray-500 mt-1">Slug genereras automatiskt från namnet</p>
                </div>

                <div>
                    <label class="form-label">Beskrivning</label>
                    <textarea name="description" rows="3" class="form-input">{{ old('description', $category->description) }}</textarea>
                </div>

                <div>
                    <label class="form-label">Ikon (emoji)</label>
                    <input type="text" name="icon" value="{{ old('icon', $category->icon) }}" class="form-input" maxlength="10">
                    <p class="text-xs text-gray-500 mt-1">Nuvarande: {{ $category->icon ?? 'Ingen ikon' }}</p>
                </div>

                <div>
                    <label class="form-label">Bild</label>
                    @if($category->image)
                        <div class="mb-2">
                            <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}" class="w-32 h-32 object-cover rounded">
                        </div>
                    @endif
                    <input type="file" name="image" accept="image/*" class="form-input">
                </div>

                <div>
                    <label class="form-label">Sorteringsordning</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" min="0" class="form-input">
                    <p class="text-xs text-gray-500 mt-1">Lägre nummer visas först</p>
                </div>

                <div>
                    <label class="form-label">Status *</label>
                    <select name="status" required class="form-input">
                        <option value="active" {{ $category->status === 'active' ? 'selected' : '' }}>Aktiv</option>
                        <option value="inactive" {{ $category->status === 'inactive' ? 'selected' : '' }}>Inaktiv</option>
                    </select>
                </div>

                <!-- SEO Content Sections -->
                <div class="border-t pt-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">SEO & Innehåll</h3>
                    
                    <!-- Intro Paragraph -->
                    <div class="mb-6">
                        <label class="form-label">Intro-paragraf (40-60 ord)</label>
                        <textarea name="intro_paragraph" rows="3" class="form-input" placeholder="Kort introduktion med nyckelord...">{{ old('intro_paragraph', $category->intro_paragraph) }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Sammanfatta vad tjänsten är, vem den är för och fördelen</p>
                    </div>

                    <!-- Features/Benefits -->
                    <div class="mb-6">
                        <label class="form-label">Fördelar & Funktioner</label>
                        <div id="features-container">
                            @if($category->features_benefits && count($category->features_benefits) > 0)
                                @foreach($category->features_benefits as $index => $feature)
                                    <div class="flex gap-2 mb-2 feature-item">
                                        <input type="text" name="features_benefits[{{ $index }}][title]" 
                                               value="{{ $feature['title'] ?? '' }}" 
                                               placeholder="Titel" class="form-input flex-1">
                                        <input type="text" name="features_benefits[{{ $index }}][description]" 
                                               value="{{ $feature['description'] ?? $feature }}" 
                                               placeholder="Beskrivning" class="form-input flex-2">
                                        <button type="button" onclick="removeFeature(this)" class="btn btn-danger">-</button>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" onclick="addFeature()" class="btn btn-secondary">+ Lägg till fördel</button>
                    </div>

                    <!-- Process Steps -->
                    <div class="mb-6">
                        <label class="form-label">Process / Så här fungerar det</label>
                        <div id="process-container">
                            @if($category->process_steps && count($category->process_steps) > 0)
                                @foreach($category->process_steps as $index => $step)
                                    <div class="flex gap-2 mb-2 process-item">
                                        <input type="text" name="process_steps[{{ $index }}][title]" 
                                               value="{{ $step['title'] ?? '' }}" 
                                               placeholder="Steg {{ $index + 1 }} titel" class="form-input flex-1">
                                        <input type="text" name="process_steps[{{ $index }}][description]" 
                                               value="{{ $step['description'] ?? $step }}" 
                                               placeholder="Beskrivning" class="form-input flex-2">
                                        <button type="button" onclick="removeProcess(this)" class="btn btn-danger">-</button>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" onclick="addProcess()" class="btn btn-secondary">+ Lägg till steg</button>
                    </div>

                    <!-- FAQ Items -->
                    <div class="mb-6">
                        <label class="form-label">Vanliga frågor (FAQ)</label>
                        <div id="faq-container">
                            @if($category->faq_items && count($category->faq_items) > 0)
                                @foreach($category->faq_items as $index => $faq)
                                    <div class="border border-gray-200 rounded-lg p-4 mb-2 faq-item">
                                        <input type="text" name="faq_items[{{ $index }}][question]" 
                                               value="{{ $faq['question'] ?? '' }}" 
                                               placeholder="Fråga" class="form-input mb-2">
                                        <textarea name="faq_items[{{ $index }}][answer]" 
                                                  placeholder="Svar" class="form-input" rows="2">{{ $faq['answer'] ?? $faq }}</textarea>
                                        <button type="button" onclick="removeFaq(this)" class="btn btn-danger mt-2">- Ta bort FAQ</button>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" onclick="addFaq()" class="btn btn-secondary">+ Lägg till FAQ</button>
                    </div>

                    <!-- Testimonials -->
                    <div class="mb-6">
                        <label class="form-label">Testimonials / Kundrecensioner</label>
                        <div id="testimonials-container">
                            @if($category->testimonials && count($category->testimonials) > 0)
                                @foreach($category->testimonials as $index => $testimonial)
                                    <div class="border border-gray-200 rounded-lg p-4 mb-2 testimonial-item">
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2 mb-2">
                                            <input type="text" name="testimonials[{{ $index }}][name]" 
                                                   value="{{ $testimonial['name'] ?? '' }}" 
                                                   placeholder="Kundnamn" class="form-input">
                                            <input type="text" name="testimonials[{{ $index }}][location]" 
                                                   value="{{ $testimonial['location'] ?? '' }}" 
                                                   placeholder="Plats" class="form-input">
                                        </div>
                                        <textarea name="testimonials[{{ $index }}][content]" 
                                                  placeholder="Testimonial text" class="form-input" rows="2">{{ $testimonial['content'] ?? $testimonial }}</textarea>
                                        <button type="button" onclick="removeTestimonial(this)" class="btn btn-danger mt-2">- Ta bort testimonial</button>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" onclick="addTestimonial()" class="btn btn-secondary">+ Lägg till testimonial</button>
                    </div>

                    <!-- CTA Section -->
                    <div class="mb-6">
                        <label class="form-label">Call-to-Action</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <input type="text" name="cta_text" 
                                       value="{{ old('cta_text', $category->cta_text) }}" 
                                       placeholder="CTA text" class="form-input mb-2">
                                <input type="text" name="cta_button_text" 
                                       value="{{ old('cta_button_text', $category->cta_button_text) }}" 
                                       placeholder="Knapptext" class="form-input mb-2">
                                <input type="url" name="cta_button_url" 
                                       value="{{ old('cta_button_url', $category->cta_button_url) }}" 
                                       placeholder="Knapp URL" class="form-input">
                            </div>
                        </div>
                    </div>

                    <!-- Meta Tags -->
                    <div class="mb-6">
                        <label class="form-label">Meta Keywords</label>
                        <input type="text" name="meta_keywords" 
                               value="{{ old('meta_keywords', $category->meta_keywords) }}" 
                               placeholder="nyckelord1, nyckelord2, nyckelord3" class="form-input">
                    </div>

                    <!-- Open Graph -->
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-900 mb-3">Open Graph (Facebook)</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <input type="text" name="og_title" 
                                       value="{{ old('og_title', $category->og_title) }}" 
                                       placeholder="OG Titel" class="form-input mb-2">
                                <textarea name="og_description" 
                                          placeholder="OG Beskrivning" class="form-input" rows="2">{{ old('og_description', $category->og_description) }}</textarea>
                            </div>
                            <div>
                                <label class="form-label">OG Bild</label>
                                @if($category->og_image)
                                    <div class="mb-2">
                                        <img src="{{ Storage::url($category->og_image) }}" alt="OG Image" class="w-32 h-32 object-cover rounded">
                                    </div>
                                @endif
                                <input type="file" name="og_image" accept="image/*" class="form-input">
                            </div>
                        </div>
                    </div>

                    <!-- Twitter -->
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-900 mb-3">Twitter</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <input type="text" name="twitter_title" 
                                       value="{{ old('twitter_title', $category->twitter_title) }}" 
                                       placeholder="Twitter Titel" class="form-input mb-2">
                                <textarea name="twitter_description" 
                                          placeholder="Twitter Beskrivning" class="form-input" rows="2">{{ old('twitter_description', $category->twitter_description) }}</textarea>
                            </div>
                            <div>
                                <label class="form-label">Twitter Bild</label>
                                @if($category->twitter_image)
                                    <div class="mb-2">
                                        <img src="{{ Storage::url($category->twitter_image) }}" alt="Twitter Image" class="w-32 h-32 object-cover rounded">
                                    </div>
                                @endif
                                <input type="file" name="twitter_image" accept="image/*" class="form-input">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3">📊 Statistik</h4>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-600">Antal tjänster:</p>
                            <p class="font-semibold text-lg">{{ $category->services_count }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Skapad:</p>
                            <p class="font-semibold">{{ $category->created_at->format('Y-m-d') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-center mt-6 pt-6 border-t">
                @if($category->services_count === 0)
                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Är du säker på att du vill radera denna kategori?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            Radera kategori
                        </button>
                    </form>
                @else
                    <div class="text-sm text-gray-500">
                        Kan inte radera kategori med tjänster
                    </div>
                @endif

                <div class="flex space-x-4">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                        Avbryt
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Uppdatera kategori
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
let featureIndex = {{ $category->features_benefits ? count($category->features_benefits) : 0 }};
let processIndex = {{ $category->process_steps ? count($category->process_steps) : 0 }};
let faqIndex = {{ $category->faq_items ? count($category->faq_items) : 0 }};
let testimonialIndex = {{ $category->testimonials ? count($category->testimonials) : 0 }};

function addFeature() {
    const container = document.getElementById('features-container');
    const div = document.createElement('div');
    div.className = 'flex gap-2 mb-2 feature-item';
    div.innerHTML = `
        <input type="text" name="features_benefits[${featureIndex}][title]" placeholder="Titel" class="form-input flex-1">
        <input type="text" name="features_benefits[${featureIndex}][description]" placeholder="Beskrivning" class="form-input flex-2">
        <button type="button" onclick="removeFeature(this)" class="btn btn-danger">-</button>
    `;
    container.appendChild(div);
    featureIndex++;
}

function removeFeature(button) {
    button.parentElement.remove();
}

function addProcess() {
    const container = document.getElementById('process-container');
    const div = document.createElement('div');
    div.className = 'flex gap-2 mb-2 process-item';
    div.innerHTML = `
        <input type="text" name="process_steps[${processIndex}][title]" placeholder="Steg ${processIndex + 1} titel" class="form-input flex-1">
        <input type="text" name="process_steps[${processIndex}][description]" placeholder="Beskrivning" class="form-input flex-2">
        <button type="button" onclick="removeProcess(this)" class="btn btn-danger">-</button>
    `;
    container.appendChild(div);
    processIndex++;
}

function removeProcess(button) {
    button.parentElement.remove();
}

function addFaq() {
    const container = document.getElementById('faq-container');
    const div = document.createElement('div');
    div.className = 'border border-gray-200 rounded-lg p-4 mb-2 faq-item';
    div.innerHTML = `
        <input type="text" name="faq_items[${faqIndex}][question]" placeholder="Fråga" class="form-input mb-2">
        <textarea name="faq_items[${faqIndex}][answer]" placeholder="Svar" class="form-input" rows="2"></textarea>
        <button type="button" onclick="removeFaq(this)" class="btn btn-danger mt-2">- Ta bort FAQ</button>
    `;
    container.appendChild(div);
    faqIndex++;
}

function removeFaq(button) {
    button.parentElement.remove();
}

function addTestimonial() {
    const container = document.getElementById('testimonials-container');
    const div = document.createElement('div');
    div.className = 'border border-gray-200 rounded-lg p-4 mb-2 testimonial-item';
    div.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-3 gap-2 mb-2">
            <input type="text" name="testimonials[${testimonialIndex}][name]" placeholder="Kundnamn" class="form-input">
            <input type="text" name="testimonials[${testimonialIndex}][location]" placeholder="Plats" class="form-input">
        </div>
        <textarea name="testimonials[${testimonialIndex}][content]" placeholder="Testimonial text" class="form-input" rows="2"></textarea>
        <button type="button" onclick="removeTestimonial(this)" class="btn btn-danger mt-2">- Ta bort testimonial</button>
    `;
    container.appendChild(div);
    testimonialIndex++;
}

function removeTestimonial(button) {
    button.parentElement.remove();
}
</script>
@endsection

