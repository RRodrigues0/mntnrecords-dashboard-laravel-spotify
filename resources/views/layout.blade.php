<!DOCTYPE html>
<html lang="en">

<head>
    @include('includes.head')
</head>

<body @isset($lastMonth) data-month="{!! $lastMonth !!}" @endif class="{!! $class !!}">
    <div class="grid container">
        {!! $content !!}
    </div>

    @vite(['resources/js/app.js'])
</body>

</html>
