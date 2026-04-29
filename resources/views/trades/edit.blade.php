@extends('layouts.app')

@section('title', 'Edit Trade')

@section('content')
<div class="max-w-3xl mx-auto">
    <h1 class="text-3xl font-bold text-slate-800 mb-6">Edit Trade</h1>
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('trades.update', $trade) }}" method="POST">
            @method('PUT')
            @include('trades._form')
        </form>
    </div>
</div>
@endsection
