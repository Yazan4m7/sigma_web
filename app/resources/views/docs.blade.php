<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Docs</title>
</head>
<body>
    <div class="container" style="padding: 2rem;">
        {!! $content !!}
    </div>
</body>
</html>
