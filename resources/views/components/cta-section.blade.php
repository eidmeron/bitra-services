<!-- CTA Section -->
<section class="py-16 lg:py-24 bg-gradient-to-br from-blue-600 via-purple-600 to-pink-500 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-4xl lg:text-5xl font-bold mb-6">
                Få en offert nu med enkelhet och transparens
            </h2>
            <p class="text-xl text-blue-100 mb-8 max-w-3xl mx-auto">
                Vi garanterar kvaliteten på vår service. De bästa priserna någonsin, utan konkurrens, i Sverige. 
                Vi erbjuder också periodiska rabatter.
            </p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                <!-- Easy Booking -->
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6">
                    <div class="text-4xl mb-4">📱</div>
                    <h3 class="text-xl font-bold mb-2">Enkelt att boka</h3>
                    <p class="text-blue-100 text-sm">Bara några klick och du har bokat din tjänst</p>
                </div>
                
                <!-- Best Companies -->
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6">
                    <div class="text-4xl mb-4">⭐</div>
                    <h3 class="text-xl font-bold mb-2">Bästa företagen</h3>
                    <p class="text-blue-100 text-sm">Verifierade partners med hög kvalitet</p>
                </div>
                
                <!-- Points & Rewards -->
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6">
                    <div class="text-4xl mb-4">🎁</div>
                    <h3 class="text-xl font-bold mb-2">Poäng & rabatter</h3>
                    <p class="text-blue-100 text-sm">Samla poäng och få rabatter</p>
                </div>
                
                <!-- Any Service -->
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6">
                    <div class="text-4xl mb-4">🛠️</div>
                    <h3 class="text-xl font-bold mb-2">Alla tjänster</h3>
                    <p class="text-blue-100 text-sm">Från städning till renovering</p>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('public.categories') }}" 
                   class="inline-flex items-center px-8 py-4 bg-white text-blue-600 font-bold rounded-xl hover:bg-gray-100 transition-colors duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    <span class="mr-2">🚀</span>
                    Börja boka nu
                </a>
                <a href="{{ route('contact') }}" 
                   class="inline-flex items-center px-8 py-4 bg-transparent border-2 border-white text-white font-bold rounded-xl hover:bg-white hover:text-blue-600 transition-colors duration-300">
                    <span class="mr-2">📞</span>
                    Kontakta oss
                </a>
            </div>
        </div>
    </div>
</section>
