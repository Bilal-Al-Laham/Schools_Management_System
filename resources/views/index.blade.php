<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    {{-- {{ $assignments->title }} --}}
    {{-- {{ dump($assignments) }} --}}

    {{-- @if (count($assignments) > 10)
        <h1>
            {{ dd($assignments) }}
        </h1>
    @else
    <h1>
        no enough assignaments
    </h1>

    @endif --}}

    {{-- @unless ($assignments)
        <h1>
            assignmaents has been added
        </h1>
        @if (!$assignments)
            <h1>
                assignamens not found
            </h1>
        @endif
    @endunless --}}

    {{-- @forelse ($assignments as $assignment)
        {{-- {{ $loop->index }} --}}
        {{-- {{ $loop->iteration }} --}}
        {{-- {{ $loop->remaining }} --}}
        {{-- {{ $loop->count }} --}}
        {{-- {{ $loop->first }} --}}
        {{-- {{ $loop->last }} --}}
        {{-- {{ $loop->depth }} --}}
        {{-- {{ $loop->parent }}
    @empty
        <p> No assignments yet</p>
    @endforelse --}} 

    {{-- {{ $assignaments }} --}}

</body>
</html>
