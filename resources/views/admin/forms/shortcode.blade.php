@extends('layouts.admin')

@section('title', 'Kortkod och Integration')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.forms.index') }}" class="text-blue-600 hover:underline">&larr; Tillbaka till formulär</a>
</div>

<div class="max-w-4xl mx-auto">
    <div class="card mb-6">
        <h2 class="text-2xl font-bold mb-2">{{ $form->form_name }}</h2>
        <p class="text-gray-600">{{ $form->service->name }}</p>
    </div>

    <!-- Public URL -->
    <div class="card mb-6">
        <h3 class="text-xl font-semibold mb-4">📎 Publik länk</h3>
        <p class="text-gray-600 mb-4">Dela denna länk direkt med dina kunder:</p>
        <div class="bg-gray-50 p-4 rounded border flex justify-between items-center">
            <code class="text-sm">{{ $publicUrl }}</code>
            <button 
                onclick="navigator.clipboard.writeText('{{ $publicUrl }}')"
                class="btn btn-secondary ml-4"
            >
                Kopiera
            </button>
        </div>
        <div class="mt-4">
            <a href="{{ $publicUrl }}" target="_blank" class="btn btn-primary">
                Öppna formulär &rarr;
            </a>
        </div>
    </div>

    <!-- WordPress Shortcode -->
    <div class="card mb-6">
        <h3 class="text-xl font-semibold mb-4">📝 WordPress Shortcode</h3>
        <p class="text-gray-600 mb-4">
            Klistra in denna shortcode i dina WordPress-inlägg eller sidor:
        </p>
        <div class="bg-gray-50 p-4 rounded border flex justify-between items-center">
            <code class="text-sm">{{ $shortcode }}</code>
            <button 
                onclick="navigator.clipboard.writeText('{{ $shortcode }}')"
                class="btn btn-secondary ml-4"
            >
                Kopiera
            </button>
        </div>
        <div class="mt-4 text-sm text-gray-600">
            <p><strong>Installation:</strong></p>
            <ol class="list-decimal ml-6 mt-2 space-y-1">
                <li>Kopiera shortcode ovan</li>
                <li>Gå till din WordPress-editor</li>
                <li>Lägg till ett "Shortcode"-block eller klistra in direkt</li>
                <li>Spara och publicera</li>
            </ol>
        </div>
    </div>

    <!-- Embed Code -->
    <div class="card mb-6">
        <h3 class="text-xl font-semibold mb-4">🔗 JavaScript Embed</h3>
        <p class="text-gray-600 mb-4">
            Bädda in formuläret på vilken webbplats som helst med denna kod:
        </p>
        <div class="bg-gray-900 text-gray-100 p-4 rounded overflow-x-auto">
            <pre class="text-sm"><code>{{ $embedCode }}</code></pre>
        </div>
        <button 
            onclick="navigator.clipboard.writeText(`{{ addslashes($embedCode) }}`)"
            class="btn btn-secondary mt-4"
        >
            Kopiera kod
        </button>
    </div>

    <!-- iFrame Code -->
    <div class="card mb-6">
        <h3 class="text-xl font-semibold mb-4">🖼️ iFrame Embed</h3>
        <p class="text-gray-600 mb-4">
            För enklare integration kan du använda en iframe:
        </p>
        <div class="bg-gray-900 text-gray-100 p-4 rounded overflow-x-auto">
            <pre class="text-sm"><code>{{ $iframeCode }}</code></pre>
        </div>
        <button 
            onclick="navigator.clipboard.writeText(`{{ addslashes($iframeCode) }}`)"
            class="btn btn-secondary mt-4"
        >
            Kopiera kod
        </button>
    </div>

    <!-- Preview -->
    <div class="card">
        <h3 class="text-xl font-semibold mb-4">👁️ Förhandsvisning</h3>
        <p class="text-gray-600 mb-4">
            Se hur formuläret ser ut för dina kunder:
        </p>
        <a href="{{ route('admin.forms.preview', $form) }}" target="_blank" class="btn btn-primary">
            Öppna förhandsvisning
        </a>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('Kopierat till urklipp!');
    });
}
</script>
@endsection

