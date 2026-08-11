<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Innoventure 2026</title>
        
        <link rel="icon" type="image/svg+xml" href="/vite.svg" />
         <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">

        <!-- Vite React Scripts -->
        @viteReactRefresh
        @vite('resources/js/main.jsx')
    </head>
    <body>
        <div id="root"></div>
    </body>
</html>
