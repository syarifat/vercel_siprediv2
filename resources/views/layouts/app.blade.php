<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SMP Islam Tulungagung</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
    /* Hilangkan icon bawaan select di semua browser */
    select#kelas_id {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-image: none;
    }
    </style>
</head>
<body>
    @include('layouts.navigation') {{-- jika ada --}}
    @yield('scripts')
</body>
</html>