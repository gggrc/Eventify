<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Eventify' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <div class="page">
        <div class="overlay"></div>
        <div class="header fade-up">
            <h1>{{ $headerTitle }}</h1>
            <p>{{ $headerSubTitle }}</p>
        </div>
        <div class="card zoom-in">
            {{ $slot }}
        </div>
    </div>
    
    <script src="{{ asset('js/password.js') }}"></script>
</body>
</html>