<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        {{-- Offline deployment: self-hosted font + hand-rolled utility CSS.
             Replaces Bunny Fonts (Figtree) and the Vite/Tailwind bundle so
             these auth pages need no network and no Node build step. --}}
        <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/poppins/poppins.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendor/fontawesome/css/all.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/breeze-auth.css') }}">
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
