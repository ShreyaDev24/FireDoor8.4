@extends('layouts.Master')

@section('main_section')
<div class="container">
    <h2>Favorite Details</h2>
    <p><strong>Name:</strong> {{ $favorite->name }}</p>
    <p><strong>User:</strong> {{ $favorite->user->UserEmail ?? 'N/A' }}</p>
    <p><strong>Status:</strong> {{ $favorite->status }}</p>
    <br>
    <hr>
    <div class="table-responsive">
        {!! $html !!}
    </div>
</div>
@endsection
