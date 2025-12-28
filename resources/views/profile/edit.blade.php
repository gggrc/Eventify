<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile - Eventify</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;0,700;0,900;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
</head>
<body>

<div class="page">
    <div class="overlay"></div>

    <div class="header fade-up">
        <h1>Profile Settings</h1>
        <p>Update your account information and password</p>
    </div>

    <div class="card zoom-in">
        <div class="card-section">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="card-section">
            @include('profile.partials.update-password-form')
        </div>

        <div class="card-section">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>

</body>
</html>