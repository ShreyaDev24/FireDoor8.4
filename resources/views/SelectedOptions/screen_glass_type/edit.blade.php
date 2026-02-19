@extends("layouts.Master")

@section("main_section")
<div class="app-main__outer">

    <div class="app-main__inner">

        <h4 class="mb-3">Edit Screen Glass Type</h4>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <form action="{{ route('Screen-Glass-Type.update',$item->id) }}" method="POST"  enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @include('SelectedOptions.screen_glass_type._form', ['item' => $item])

            <button class="btn btn-success">Update</button>
            <a href="{{ route('Screen-Glass-Type.index') }}" class="btn btn-secondary">
                Cancel
            </a>
        </form>

    </div>
</div>
@endsection
@section('js')
<script>

</script>


@endsection
