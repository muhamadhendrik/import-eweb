@extends('layouts.back.app')
@section('title', 'Dashboard')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="my-auto d-flex justify-content-center">
        <h3>Selamat Datang, Jangan lupa input ya {{ Auth::user()->name }} 🥰</h3>
    </div>
</div>
@endsection
