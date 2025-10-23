@extends('layouts.public')

@section('title', 'Alla kategorier - Hitta din tjänst')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-r from-blue-600 to-purple-600 py-20 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-5xl md:text-6xl font-bold mb-6">
                Utforska alla kategorier
            </h1>
            <p class="text-xl md:text-2xl text-blue-100 max-w-3xl mx-auto">
                Hitta den perfekta tjänsten för dina behov. Vi har samarbetat med Sveriges bästa företag för att erbjuda tjänster inom alla kategorier.
            </p>
        </div>
    </div>
</section>

<!-- Categories Grid -->
<section class="py-16 bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        @if($categories->isEmpty())
            <div class="text-center py-12">
                <div class="text-6xl mb-4">📂</div>
                <h3 class="text-2xl font-bold text-gray-700 dark:text-gray-300 mb-2">
                    Inga kategorier tillgängliga
                </h3>
                <p class="text-gray-600 dark:text-gray-400">
                    Vi arbetar på att lägga till fler tjänster. Kom tillbaka snart!
                </p>
            </div>
        @else

            <!-- Categories Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($categories as $category)
                    <a href="{{ route('public.category.show', $category->slug) }}" 
                       class="group bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-2">
                        
                        <!-- Category Image/Icon -->
                        <div class="relative h-48 bg-gradient-to-br from-blue-400 via-purple-500 to-pink-500 flex items-center justify-center overflow-hidden">
                            @if($category->icon)
                                <img src="{{ Storage::url($category->icon) }}" 
                                     alt="{{ $category->name }}" 
                                     class="w-24 h-24 object-contain group-hover:scale-110 transition-transform duration-500">
                            @elseif($category->image)
                                <img src="{{ Storage::url($category->image) }}" 
                                     alt="{{ $category->name }}" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <span class="text-8xl group-hover:scale-110 transition-transform duration-500">
                                    {{ ['🏠', '🔧', '🌳', '🎨', '💼', '🚗', '💡', '🏗️'][array_rand(['🏠', '🔧', '🌳', '🎨', '💼', '🚗', '💡', '🏗️'])] }}
                                </span>
                            @endif
                            
                            <!-- Service Count Badge -->
                            <div class="absolute top-4 right-4 bg-white dark:bg-gray-800 rounded-full px-4 py-2 shadow-lg">
                                <span class="text-sm font-bold text-gray-700 dark:text-gray-300">
                                    {{ $category->services_count }} tjänster
                                </span>
                            </div>
                        </div>
                        
                        <!-- Category Info -->
                        <div class="p-6">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                {{ $category->name }}
                            </h3>
                            
                            <p class="text-gray-600 dark:text-gray-400 mb-4 line-clamp-3">
                                {{ $category->description ?? 'Upptäck professionella tjänster inom ' . strtolower($category->name) . '.' }}
                            </p>
                            
                            <!-- CTA -->
                            <div class="flex items-center text-blue-600 dark:text-blue-400 font-semibold group-hover:translate-x-2 transition-transform">
                                <span>Utforska tjänster</span>
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-gradient-to-r from-blue-600 to-purple-600 text-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl font-bold mb-4">
            Hittar du inte vad du söker?
        </h2>
        <p class="text-xl text-blue-100 mb-8">
            Kontakta oss så hjälper vi dig att hitta rätt tjänst
        </p>
        <a href="{{ route('contact') }}" 
           class="inline-flex items-center px-8 py-4 bg-white text-blue-600 font-bold rounded-full hover:bg-gray-100 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1">
            <span>Kontakta oss</span>
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
            </svg>
        </a>
    </div>
</section>
@endsection

