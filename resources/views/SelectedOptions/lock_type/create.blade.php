@extends("layouts.Master")

@section("main_section")
<div class="app-main__outer">

    <div class="app-main__inner">
        <h4 class="mb-3">Add Lock Type</h4>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('Lock-Type.store') }}" method="POST">
            @csrf

            <div class="card mb-3">
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label>Lock Type <span class="text-danger">*</span></label>
                            <input type="text"
                                name="LockType"
                                class="form-control @error('LockType') is-invalid @enderror"
                                value="{{ old('LockType') }}"
                                required>

                            @error('LockType')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>

                    <div class="position-relative form-group">
                        <label class="d-block">
                            Configuration <span class="text-danger">*</span>
                        </label>

                        @foreach ($brands as $name => $value)
                            <div class="form-check form-check-inline ml-2">
                                <input type="checkbox"
                                    name="brands[]"
                                    value="{{ $value }}"
                                    class="form-check-input"
                                    {{ in_array($value, old('brands', [])) ? 'checked' : '' }}>
                                <label class="form-check-label">{{ $name }}</label>
                            </div>
                        @endforeach

                        @error('brands')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </div>

            <button class="btn btn-success">Save</button>
            <a href="{{ route('Lock-Type.index') }}" class="btn btn-secondary">
                Cancel
            </a>
        </form>

    </div>
</div>
@endsection
