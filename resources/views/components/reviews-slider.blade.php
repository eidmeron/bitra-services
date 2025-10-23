@props(['reviews', 'title' => 'Vad Våra Kunder Säger'])

@if($reviews && $reviews->count() > 0)
<div class="bg-gradient-to-br from-blue-50 to-indigo-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">
                {{ $title }}
            </h2>
            <p class="text-gray-600 mb-4">
                Äkta recensioner från nöjda kunder
            </p>
            <a href="{{ route('reviews.index') }}" 
               class="inline-flex items-center text-blue-600 hover:text-blue-700 font-semibold">
                Se alla {{ \App\Models\PlatformReview::approved()->count() }} recensioner →
            </a>
        </div>

        <!-- Slider -->
        <div class="relative" x-data="{ 
            currentSlide: 0,
            totalSlides: {{ $reviews->count() }},
            autoplayInterval: null,
            startAutoplay() {
                this.autoplayInterval = setInterval(() => {
                    this.nextSlide();
                }, 5000);
            },
            stopAutoplay() {
                clearInterval(this.autoplayInterval);
            },
            nextSlide() {
                this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
            },
            prevSlide() {
                this.currentSlide = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
            }
        }" 
        x-init="startAutoplay()"
        @mouseenter="stopAutoplay()"
        @mouseleave="startAutoplay()">
            
            <!-- Reviews Container -->
            <div class="overflow-hidden">
                <div class="flex transition-transform duration-500 ease-in-out" 
                     :style="`transform: translateX(-${currentSlide * 100}%)`">
                    @foreach($reviews as $review)
                    <div class="min-w-full px-2">
                        <div class="bg-white rounded-2xl shadow-2xl p-8 md:p-12 mx-auto max-w-4xl border-4 border-blue-100">
                            <!-- Stars -->
                            <div class="flex justify-center mb-6">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->overall_rating)
                                        <svg class="w-8 h-8 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                        </svg>
                                    @else
                                        <svg class="w-8 h-8 text-gray-300 fill-current" viewBox="0 0 20 20">
                                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                        </svg>
                                    @endif
                                @endfor
                            </div>

                            <!-- Quote -->
                            <div class="text-gray-400 text-6xl leading-none mb-4 text-center">"</div>
                            
                            <!-- Comment -->
                            <p class="text-xl md:text-2xl text-gray-700 text-center italic mb-8 leading-relaxed">
                                {{ $review->comment }}
                            </p>

                            <!-- Detailed Ratings -->
                            <div class="flex flex-wrap justify-center gap-4 mb-6">
                                @if($review->service_quality_rating)
                                    <div class="flex items-center px-4 py-2 bg-blue-50 rounded-full">
                                        <span class="text-sm font-semibold text-blue-900 mr-2">Kvalitet:</span>
                                        <span class="text-sm font-bold text-blue-600">{{ $review->service_quality_rating }}/5</span>
                                    </div>
                                @endif
                                @if($review->price_rating)
                                    <div class="flex items-center px-4 py-2 bg-green-50 rounded-full">
                                        <span class="text-sm font-semibold text-green-900 mr-2">Pris:</span>
                                        <span class="text-sm font-bold text-green-600">{{ $review->price_rating }}/5</span>
                                    </div>
                                @endif
                                @if($review->speed_rating)
                                    <div class="flex items-center px-4 py-2 bg-purple-50 rounded-full">
                                        <span class="text-sm font-semibold text-purple-900 mr-2">Snabbhet:</span>
                                        <span class="text-sm font-bold text-purple-600">{{ $review->speed_rating }}/5</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Author -->
                            <div class="flex items-center justify-center space-x-4">
                                <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-2xl shadow-lg">
                                    {{ strtoupper(substr($review->name, 0, 1)) }}
                                </div>
                                <div class="text-left">
                                    <div class="font-bold text-gray-900 text-lg">{{ $review->name }}</div>
                                    <div class="text-gray-500 text-sm">{{ $review->created_at->diffForHumans() }}</div>
                                </div>
                            </div>

                            @if($review->is_featured)
                                <div class="mt-6 text-center">
                                    <span class="inline-block px-4 py-2 bg-yellow-100 text-yellow-800 text-sm font-bold rounded-full">
                                        ⭐ Utvald Recension
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Navigation Arrows -->
            @if($reviews->count() > 1)
            <button @click="prevSlide(); stopAutoplay();" 
                    class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 md:-translate-x-12 w-14 h-14 bg-white rounded-full shadow-2xl hover:bg-gray-50 transition flex items-center justify-center text-gray-700 hover:scale-110 z-10 border-2 border-blue-200">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            <button @click="nextSlide(); stopAutoplay();" 
                    class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 md:translate-x-12 w-14 h-14 bg-white rounded-full shadow-2xl hover:bg-gray-50 transition flex items-center justify-center text-gray-700 hover:scale-110 z-10 border-2 border-blue-200">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/>
                </svg>
            </button>

            <!-- Dots Indicator -->
            <div class="flex justify-center mt-8 space-x-2">
                <template x-for="i in totalSlides" :key="i">
                    <button @click="currentSlide = i - 1; stopAutoplay();" 
                            class="w-3 h-3 rounded-full transition-all"
                            :class="currentSlide === (i - 1) ? 'bg-blue-600 w-10' : 'bg-gray-300'">
                    </button>
                </template>
            </div>
            @endif
        </div>

        <!-- CTA Button -->
        <div class="text-center mt-12">
            <a href="{{ route('reviews.index') }}" 
               class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-bold text-lg rounded-lg hover:from-blue-700 hover:to-purple-700 transition shadow-xl hover:shadow-2xl">
                ✍️ Skriv Din Recension
            </a>
        </div>
    </div>
</div>
@endif

