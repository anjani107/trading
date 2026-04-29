@extends('layouts.app')

@section('title', 'New Trade')

@section('content')
<div class="max-w-3xl mx-auto">
    <h1 class="text-3xl font-bold text-slate-800 mb-6">Log New Trade</h1>
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('trades.store') }}" method="POST">
            @include('trades._form')
        </form>
    </div>
</div>
@endsection
