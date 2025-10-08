<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registrera - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Skapa ett nytt konto
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Eller
                    <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">
                        logga in på ditt konto
                    </a>
                </p>
            </div>

            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                <form class="space-y-6" method="POST" action="{{ route('register') }}">
                    @csrf

                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div>
                        <label for="email" class="form-label">E-postadress</label>
                        <input 
                            id="email" 
                            name="email" 
                            type="email" 
                            autocomplete="email" 
                            required 
                            class="form-input"
                            value="{{ old('email') }}"
                        >
                    </div>

                    <div>
                        <label for="phone" class="form-label">Telefonnummer (valfritt)</label>
                        <input 
                            id="phone" 
                            name="phone" 
                            type="tel" 
                            class="form-input"
                            value="{{ old('phone') }}"
                        >
                    </div>

                    <div>
                        <label for="password" class="form-label">Lösenord</label>
                        <input 
                            id="password" 
                            name="password" 
                            type="password" 
                            autocomplete="new-password" 
                            required 
                            class="form-input"
                        >
                    </div>

                    <div>
                        <label for="password_confirmation" class="form-label">Bekräfta lösenord</label>
                        <input 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            type="password" 
                            autocomplete="new-password" 
                            required 
                            class="form-input"
                        >
                    </div>

                    <div class="flex items-start">
                        <input 
                            id="terms" 
                            name="terms" 
                            type="checkbox" 
                            required
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded mt-1"
                        >
                        <label for="terms" class="ml-2 block text-sm text-gray-900">
                            Jag accepterar 
                            <a href="#" class="text-blue-600 hover:underline">användarvillkoren</a> 
                            och 
                            <a href="#" class="text-blue-600 hover:underline">integritetspolicyn</a>
                        </label>
                    </div>

                    <div>
                        <button type="submit" class="w-full btn btn-primary">
                            Skapa konto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
