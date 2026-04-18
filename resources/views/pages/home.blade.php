@extends('layouts.app')

@section('title', 'Inicio')

@section('content')

<div class="container py-4">

</div>

<style>
    .transition-hover {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .transition-hover:hover {
        transform: translateY(-10px);
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, .1) !important;
    }

    .bg-light-blue {
        background-color: #f8faff;
    }
</style>

@endsection