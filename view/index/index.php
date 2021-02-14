<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
</head>
<body>
@if ($url_infos)
    <ul>
@foreach ($url_infos as $url_info)
        <li><a href="{{ $url_info['href'] }}">{{ $url_info['name'] }}</a></li>
@endforeach
    </ul>
@endif
</body>
</html>
