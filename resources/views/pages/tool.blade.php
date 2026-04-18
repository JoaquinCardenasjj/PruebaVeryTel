@extends('layouts.app')

@section('title', $tool['name'])


@section('content')

<h1>{{ $tool['name'] }}</h1>
<p>{{ $tool['description'] }}</p>

@if($tool['type'] === 'js')
@include($tool['view'])
@endif

@if($tool['type'] === 'node')
<div id="tool-app"></div>
<script>
    fetch('/api/tool/{{ $tool['
            endpoint '] }}')
        .then(r => r.json())
        .then(data => {
            document.getElementById('tool-app').innerText = data.result;
        });
</script>
@endif

@endsection