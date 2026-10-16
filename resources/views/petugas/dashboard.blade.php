@extends('layouts.app')

@section('title', 'Petugas Dashboard')

@section('content')
    <h2>Petugas Dashboard</h2>
    <p>Welcome, {{ Auth::user()->name }}</p>
    <a href="/logout">Logout</a>
@endsection
