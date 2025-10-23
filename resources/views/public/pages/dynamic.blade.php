@extends('layouts.public')

@section('title', $seoTitle)
@section('description', $seoDescription)

@section('content')
<section class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-14">
    <div class="max-w-5xl mx-auto px-4">
        <h1 class="text-4xl font-extrabold mb-2">{{ $page->page_name ?? ucfirst(str_replace('-', ' ', $page->page_key)) }}</h1>
        @if($page->hero_subtitle)
            <p class="text-blue-100 text-lg">{{ $page->hero_subtitle }}</p>
        @endif
</div>
</section>

<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            @if($page->hero_image)
                <img src="{{ Storage::url($page->hero_image) }}" alt="{{ $page->page_name }}" class="rounded-2xl shadow mb-8">
            @endif

            @if(!empty($page->sections))
                @foreach($page->sections as $section)
                    <div class="mb-10">
                        @if(!empty($section['title']))
                            <h2 class="text-2xl font-bold text-gray-900 mb-3">{{ $section['title'] }}</h2>
                        @endif
                        @if(!empty($section['content']))
                            <div class="prose max-w-none">{!! nl2br(e($section['content'])) !!}</div>
                        @endif
                    </div>
                @endforeach
            @else
                <p class="text-gray-700">{{ $page->meta_description }}</p>
            @endif
        </div>

        <aside>
            <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Kategorier</h3>
                <ul class="space-y-2">
                    @foreach(\App\Models\Category::where('status','active')->orderBy('name')->take(10)->get() as $cat)
                        <li>
                            <a href="{{ route('public.categories') }}#cat-{{ $cat->id }}" class="text-gray-700 hover:text-blue-600">{{ $cat->name }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="bg-white border border-gray-200 rounded-2xl p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Boka tjänst</h3>
                <p class="text-gray-600 mb-4">Redo att komma igång? Boka din tjänst på 2 minuter.</p>
                <a href="{{ route('public.services') }}" class="block w-full text-center bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-blue-700 hover:to-purple-700">Boka nu</a>
            </div>

            <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Vanliga frågor</h3>
                <div class="space-y-3 text-gray-700">
                    <details class="bg-white rounded-lg p-4 border border-gray-200">
                        <summary class="font-medium cursor-pointer">Hur snabbt kan jag få hjälp?</summary>
                        <p class="mt-2 text-sm">Ofta samma dag eller inom 24 timmar beroende på tjänst och stad.</p>
                    </details>
                    <details class="bg-white rounded-lg p-4 border border-gray-200">
                        <summary class="font-medium cursor-pointer">Ingår material?</summary>
                        <p class="mt-2 text-sm">Standardmaterial ingår, specialmaterial offereras separat.</p>
                    </details>
                    <details class="bg-white rounded-lg p-4 border border-gray-200">
                        <summary class="font-medium cursor-pointer">Kan jag avboka?</summary>
                        <p class="mt-2 text-sm">Ja, avbokning fram till 24 timmar innan utan kostnad.</p>
                    </details>
                </div>
            </div>
        </aside>
    </div>
</section>
@endsection


