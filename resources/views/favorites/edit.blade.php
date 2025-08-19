@extends('layouts.Master')

@section('main_section')
<div class="container">
    <h2>Edit Favourite</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('favorites.update', $favorite->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Name:</label>
            <input type="text" name="name" value="{{ old('name', $favorite->name) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Status:</label>
            <select name="status" class="form-control">
                <option value="1" {{ $favorite->status == 1 ? 'selected' : '' }}>Active</option>
                <option value="0" {{ $favorite->status == 0 ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('favorites.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
