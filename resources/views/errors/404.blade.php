@extends('layouts.public')

@section('title', '404 - Sidan hittades inte')

@push('styles')
<style>
    /* 404 Page Specific Styles */
    .error-container {
        min-height: 100vh;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }
    
    .error-content {
        text-align: center;
        color: white;
        z-index: 10;
        position: relative;
    }
    
    .error-number {
        font-size: 12rem;
        font-weight: 900;
        line-height: 1;
        margin: 0;
        text-shadow: 0 0 30px rgba(255, 255, 255, 0.3);
        background: linear-gradient(45deg, #fff, #f0f0f0);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .error-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 1rem 0;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }
    
    .error-description {
        font-size: 1.25rem;
        margin: 1rem 0 2rem;
        opacity: 0.9;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .error-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
        margin-top: 2rem;
    }
    
    .btn-primary {
        background: rgba(255, 255, 255, 0.2);
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: white;
        padding: 12px 24px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }
    
    .btn-primary:hover {
        background: rgba(255, 255, 255, 0.3);
        border-color: rgba(255, 255, 255, 0.5);
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }
    
    .btn-secondary {
        background: transparent;
        border: 2px solid rgba(255, 255, 255, 0.5);
        color: white;
        padding: 12px 24px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-secondary:hover {
        background: rgba(255, 255, 255, 0.1);
        transform: translateY(-2px);
    }
    
    /* Floating Elements */
    .floating-element {
        position: absolute;
        opacity: 0.1;
        animation: float 6s ease-in-out infinite;
    }
    
    .floating-element:nth-child(1) {
        top: 20%;
        left: 10%;
        animation-delay: 0s;
    }
    
    .floating-element:nth-child(2) {
        top: 60%;
        right: 10%;
        animation-delay: 2s;
    }
    
    .floating-element:nth-child(3) {
        bottom: 20%;
        left: 20%;
        animation-delay: 4s;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(180deg); }
    }
    
    /* Search Box */
    .search-box {
        background: rgba(255, 255, 255, 0.1);
        border: 2px solid rgba(255, 255, 255, 0.2);
        border-radius: 50px;
        padding: 8px;
        margin: 2rem auto;
        max-width: 500px;
        backdrop-filter: blur(10px);
    }
    
    .search-input {
        background: transparent;
        border: none;
        color: white;
        padding: 12px 20px;
        width: 100%;
        font-size: 1rem;
    }
    
    .search-input::placeholder {
        color: rgba(255, 255, 255, 0.7);
    }
    
    .search-input:focus {
        outline: none;
    }
    
    .search-btn {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        padding: 12px 20px;
        border-radius: 50px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .search-btn:hover {
        background: rgba(255, 255, 255, 0.3);
    }
    
    /* Popular Links */
    .popular-links {
        margin-top: 3rem;
        padding-top: 2rem;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .popular-links h3 {
        font-size: 1.5rem;
        margin-bottom: 1rem;
        opacity: 0.9;
    }
    
    .links-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }
    
    .link-item {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 10px;
        padding: 1rem;
        text-decoration: none;
        color: white;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }
    
    .link-item:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-2px);
    }
    
    .link-item h4 {
        margin: 0 0 0.5rem 0;
        font-size: 1.1rem;
    }
    
    .link-item p {
        margin: 0;
        opacity: 0.8;
        font-size: 0.9rem;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .error-number {
            font-size: 8rem;
        }
        
        .error-title {
            font-size: 2rem;
        }
        
        .error-description {
            font-size: 1.1rem;
        }
        
        .error-actions {
            flex-direction: column;
            align-items: center;
        }
        
        .btn-primary, .btn-secondary {
            width: 100%;
            max-width: 300px;
        }
    }
</style>
@endpush

@section('content')
<div class="error-container bg-gradient-to-br from-blue-600 via-purple-600 to-pink-500">
    <!-- Floating Background Elements -->
    <div class="floating-element" style="font-size: 4rem;">🏠</div>
    <div class="floating-element" style="font-size: 3rem;">🔧</div>
    <div class="floating-element" style="font-size: 5rem;">🧹</div>
    <div class="floating-element" style="font-size: 3.5rem;">🚚</div>
    <div class="floating-element" style="font-size: 4.5rem;">💡</div>
    
    <div class="error-content">
        <h1 class="error-number">404</h1>
        <h2 class="error-title">Oj! Sidan hittades inte</h2>
        <p class="error-description">
            Den sida du letar efter verkar ha försvunnit eller flyttats. 
            Men oroa dig inte - vi hjälper dig att hitta det du söker!
        </p>
        
        <!-- Search Box -->
        <div class="search-box">
            <form action="{{ route('public.search') }}" method="GET" style="display: flex; align-items: center;">
                <input type="text" 
                       name="q" 
                       placeholder="Sök efter tjänster, städer eller företag..." 
                       class="search-input"
                       value="{{ request('q') }}">
                <button type="submit" class="search-btn">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
            </form>
        </div>
        
        <!-- Action Buttons -->
        <div class="error-actions">
            <a href="{{ url('/') }}" class="btn-primary">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right: 8px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Tillbaka till startsidan
            </a>
            <a href="{{ route('public.services') }}" class="btn-secondary">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right: 8px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                Bläddra tjänster
            </a>
        </div>
        
        <!-- Popular Links -->
        <div class="popular-links">
            <h3>Populära sidor</h3>
            <div class="links-grid">
                <a href="{{ route('public.services') }}" class="link-item">
                    <h4>🔧 Alla tjänster</h4>
                    <p>Utforska vårt breda utbud av professionella tjänster</p>
                </a>
                <a href="{{ route('public.cities') }}" class="link-item">
                    <h4>📍 Våra städer</h4>
                    <p>Hitta tjänster i din stad eller närliggande områden</p>
                </a>
                <a href="{{ route('reviews.index') }}" class="link-item">
                    <h4>⭐ Recensioner</h4>
                    <p>Läs vad våra kunder säger om våra tjänster</p>
                </a>
                <a href="{{ route('contact') }}" class="link-item">
                    <h4>📞 Kontakta oss</h4>
                    <p>Behöver du hjälp? Vi finns här för dig</p>
                </a>
                <a href="{{ route('how-it-works') }}" class="link-item">
                    <h4>❓ Så fungerar det</h4>
                    <p>Lär dig hur enkelt det är att boka tjänster</p>
                </a>
                <a href="{{ route('why-us') }}" class="link-item">
                    <h4>💡 Varför välja oss</h4>
                    <p>Upptäck fördelarna med att använda Bitra Services</p>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Add some interactive elements
document.addEventListener('DOMContentLoaded', function() {
    // Animate the 404 number
    const errorNumber = document.querySelector('.error-number');
    if (errorNumber) {
        errorNumber.style.opacity = '0';
        errorNumber.style.transform = 'scale(0.5)';
        
        setTimeout(() => {
            errorNumber.style.transition = 'all 0.8s ease-out';
            errorNumber.style.opacity = '1';
            errorNumber.style.transform = 'scale(1)';
        }, 200);
    }
    
    // Add hover effects to floating elements
    const floatingElements = document.querySelectorAll('.floating-element');
    floatingElements.forEach((element, index) => {
        element.addEventListener('mouseenter', function() {
            this.style.animationPlayState = 'paused';
            this.style.transform = 'scale(1.2)';
        });
        
        element.addEventListener('mouseleave', function() {
            this.style.animationPlayState = 'running';
            this.style.transform = 'scale(1)';
        });
    });
    
    // Focus search input on page load
    const searchInput = document.querySelector('.search-input');
    if (searchInput) {
        setTimeout(() => {
            searchInput.focus();
        }, 1000);
    }
});
</script>
@endpush
