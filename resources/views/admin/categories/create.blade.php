@extends('layouts.admin')

@section('title', 'Skapa kategori')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.categories.index') }}" class="text-blue-600 hover:underline">&larr; Tillbaka till kategorier</a>
</div>

<div class="max-w-2xl mx-auto">
    <div class="card">
        <h2 class="text-2xl font-bold mb-6">Skapa ny kategori</h2>

        <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data">
            @csrf

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded mb-6">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="space-y-6">
                <div>
                    <label class="form-label">Kategorinamn *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="form-input" placeholder="t.ex. Städning">
                    <p class="text-xs text-gray-500 mt-1">Namnet på kategorin</p>
                </div>

                <div>
                    <label class="form-label">Beskrivning</label>
                    <textarea name="description" rows="3" class="form-input" placeholder="Beskriv vilka typer av tjänster som ingår i denna kategori...">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label class="form-label">Ikon (emoji)</label>
                    <input type="text" name="icon" value="{{ old('icon') }}" class="form-input" placeholder="🧹" maxlength="10">
                    <p class="text-xs text-gray-500 mt-1">Emoji som representerar kategorin. Exempel: 🧹 🔧 🌱 📦</p>
                </div>

                <div>
                    <label class="form-label">Bild</label>
                    <input type="file" name="image" accept="image/*" class="form-input">
                    <p class="text-xs text-gray-500 mt-1">Max 2MB. Format: JPG, PNG, GIF</p>
                </div>

                <div>
                    <label class="form-label">Sorteringsordning</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" class="form-input">
                    <p class="text-xs text-gray-500 mt-1">Lägre nummer visas först. Standard: 0</p>
                </div>

                <div>
                    <label class="form-label">Status *</label>
                    <select name="status" required class="form-input">
                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Aktiv</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inaktiv</option>
                    </select>
                </div>

                <!-- SEO Content Sections -->
                <div class="border-t pt-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">SEO & Innehåll</h3>
                    
                    <!-- Intro Paragraph -->
                    <div class="mb-6">
                        <label class="form-label">Intro-paragraf (40-60 ord)</label>
                        <textarea name="intro_paragraph" rows="3" class="form-input" placeholder="Kort introduktion med nyckelord...">{{ old('intro_paragraph') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Sammanfatta vad tjänsten är, vem den är för och fördelen</p>
                    </div>

                    <!-- Features/Benefits -->
                    <div class="mb-6">
                        <label class="form-label">Fördelar & Funktioner</label>
                        <div id="features-container"></div>
                        <button type="button" onclick="addFeature()" class="btn btn-secondary">+ Lägg till fördel</button>
                    </div>

                    <!-- Process Steps -->
                    <div class="mb-6">
                        <label class="form-label">Process / Så här fungerar det</label>
                        <div id="process-container"></div>
                        <button type="button" onclick="addProcess()" class="btn btn-secondary">+ Lägg till steg</button>
                    </div>

                    <!-- FAQ Items -->
                    <div class="mb-6">
                        <label class="form-label">Vanliga frågor (FAQ)</label>
                        <div id="faq-container"></div>
                        <button type="button" onclick="addFaq()" class="btn btn-secondary">+ Lägg till FAQ</button>
                    </div>

                    <!-- Testimonials -->
                    <div class="mb-6">
                        <label class="form-label">Testimonials / Kundrecensioner</label>
                        <div id="testimonials-container"></div>
                        <button type="button" onclick="addTestimonial()" class="btn btn-secondary">+ Lägg till testimonial</button>
                    </div>

                    <!-- CTA Section -->
                    <div class="mb-6">
                        <label class="form-label">Call-to-Action</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <input type="text" name="cta_text" 
                                       value="{{ old('cta_text') }}" 
                                       placeholder="CTA text" class="form-input mb-2">
                                <input type="text" name="cta_button_text" 
                                       value="{{ old('cta_button_text') }}" 
                                       placeholder="Knapptext" class="form-input mb-2">
                                <input type="url" name="cta_button_url" 
                                       value="{{ old('cta_button_url') }}" 
                                       placeholder="Knapp URL" class="form-input">
                            </div>
                        </div>
                    </div>

                    <!-- Meta Tags -->
                    <div class="mb-6">
                        <label class="form-label">Meta Keywords</label>
                        <input type="text" name="meta_keywords" 
                               value="{{ old('meta_keywords') }}" 
                               placeholder="nyckelord1, nyckelord2, nyckelord3" class="form-input">
                    </div>

                    <!-- Open Graph -->
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-900 mb-3">Open Graph (Facebook)</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <input type="text" name="og_title" 
                                       value="{{ old('og_title') }}" 
                                       placeholder="OG Titel" class="form-input mb-2">
                                <textarea name="og_description" 
                                          placeholder="OG Beskrivning" class="form-input" rows="2">{{ old('og_description') }}</textarea>
                            </div>
                            <div>
                                <label class="form-label">OG Bild</label>
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
                                       value="{{ old('twitter_title') }}" 
                                       placeholder="Twitter Titel" class="form-input mb-2">
                                <textarea name="twitter_description" 
                                          placeholder="Twitter Beskrivning" class="form-input" rows="2">{{ old('twitter_description') }}</textarea>
                            </div>
                            <div>
                                <label class="form-label">Twitter Bild</label>
                                <input type="file" name="twitter_image" accept="image/*" class="form-input">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <h4 class="font-semibold text-green-900 mb-2">✨ Förslag på kategorier</h4>
                    <div class="text-sm text-green-800 space-y-1">
                        <p>• 🧹 Städning - Hemstädning, kontorsstädning, flyttstädning</p>
                        <p>• 🔧 Hantverkare - VVS, el, snickare, målare</p>
                        <p>• 🌱 Trädgård - Gräsklippning, beskärning, trädgårdsdesign</p>
                        <p>• 📦 Flytt - Flytthjälp, packning, magasinering</p>
                        <p>• 🛠️ Reparationer - Vitvaror, elektronik, möbler</p>
                        <p>• 🚗 Fordon - Biltvätt, däckskifte, service</p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-6 pt-6 border-t">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                    Avbryt
                </a>
                <button type="submit" class="btn btn-primary">
                    Skapa kategori
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let featureIndex = 0;
let processIndex = 0;
let faqIndex = 0;
let testimonialIndex = 0;

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

