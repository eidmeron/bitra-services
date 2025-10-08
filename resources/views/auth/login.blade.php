<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Logga in - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Logga in på ditt konto
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Eller
                    <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500">
                        skapa ett nytt konto
                    </a>
                </p>
            </div>

            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                <form class="space-y-6" method="POST" action="{{ route('login') }}">
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
                        <label for="password" class="form-label">Lösenord</label>
                        <input 
                            id="password" 
                            name="password" 
                            type="password" 
                            autocomplete="current-password" 
                            required 
                            class="form-input"
                        >
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input 
                                id="remember_me" 
                                name="remember" 
                                type="checkbox" 
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                            >
                            <label for="remember_me" class="ml-2 block text-sm text-gray-900">
                                Kom ihåg mig
                            </label>
                        </div>

                        <div class="text-sm">
                            <a href="#" class="font-medium text-blue-600 hover:text-blue-500">
                                Glömt lösenord?
                            </a>
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="w-full btn btn-primary">
                            Logga in
                        </button>
                    </div>
                </form>

                <div class="mt-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">
                                Test-inloggningar
                            </span>
                        </div>
                    </div>

                    <div class="mt-6 text-sm text-gray-600">
                        <p class="mb-2"><strong>Admin:</strong> admin@bitratjanster.se / password</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
