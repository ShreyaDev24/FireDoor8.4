@extends('layouts.Master')

@section('main_section')
<div class="container">
    <h2>Create Favourite</h2>
    <form action="{{ route('favorites.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Name:</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <button class="btn btn-success">Save</button>
    </form>
</div>
@endsection
