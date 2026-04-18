@extends('layouts.app')

@section('title', 'Inicio')



@section('content')

<div class="container-fluid" id="tools-container">

    <div id="grid-selector">

    </div>

    <div id="form-container" class="d-none">

    </div>
</div>

<style>
    .card-tool {
        cursor: pointer;
        transition: 0.3s;
    }

    .card-tool:hover {
        transform: scale(1.03);
        border-color: #0d6efd;
    }
</style>
<script>

</script>
<script src="{{ asset('tickets.js') }}"></script>

@endsection