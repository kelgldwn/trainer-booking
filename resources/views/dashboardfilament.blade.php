<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Dashboard Redirect</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }

        .dashboard-btn {
            padding: 1rem 2rem;
            font-size: 1.2rem;
            font-weight: 600;
            background-color: #4f46e5;
            color: white;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            text-decoration: none;
        }

        .dashboard-btn:hover {
            background-color: #3730a3;
        }
    </style>
</head>
<body>

    @auth
        @php
            $user = auth()->user();
            if ($user->hasRole('admin')) {
                $dashboardUrl = route('filament.admin.pages.dashboard');
            } elseif ($user->hasRole('coach')) {
                $dashboardUrl = route('filament.coach.pages.dashboard');
            } elseif ($user->hasRole('client')) {
                $dashboardUrl = route('filament.client.pages.dashboard');
            } else {
                $dashboardUrl = '#';
            }
        @endphp

        <a href="{{ $dashboardUrl }}" class="dashboard-btn">Go to Filament Dashboard</a>
    @else
        <a href="{{ route('login') }}" class="dashboard-btn">Login</a>
    @endauth

</body>
</html>
