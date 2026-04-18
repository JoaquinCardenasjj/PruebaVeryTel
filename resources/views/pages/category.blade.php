@extends('layouts.app')

@section('title', $cat['name'])

@section('content')

<h1>{{ $cat['name'] }}</h1>

@endsection