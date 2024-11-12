<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Jogo de Cartas')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="game-table">
    @yield('content')

   
    <script>
            @yield('scripts')
    </script>
</body>
</html>
