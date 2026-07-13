<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="ChatBox – Real-time messaging for teams and individuals">
    <title>ChatBox</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-[#0d0f14] text-white antialiased">
    {{ $slot }}
</body>
</html>