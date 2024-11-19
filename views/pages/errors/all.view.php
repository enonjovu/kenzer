<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$message}} - {{$code}}</title>

    <link rel="stylesheet" href="/assets/build/style.css">
</head>

<body>
    <h1>{{$code}} - {{$message}}</h1>

    <ul>

        @foreach($trace as $stack)
        <li>
            @isset($stack['line'])
            <div class="w-full">
                <div>
                    {{$stack['file']}}:{{$stack['line']}}
                </div>
                <div>
                    {{$stack['class']}}{{$stack['type']}}{{$stack['function']}}()
                </div>
            </div>
            @else
            <div class="w-full">
                {{$stack['class']}}{{$stack['type']}}{{$stack['function']}}()
            </div>
            @endisset
        </li>
        @endforeach
    </ul>
</body>

</html>