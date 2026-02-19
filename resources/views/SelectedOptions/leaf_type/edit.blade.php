@extends("layouts.Master")

@section("main_section")
<div class="app-main__outer">

    <div class="app-main__inner">

        <h4 class="mb-3">Edit Leaf Type</h4>

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


        <form action="{{ route('leaf-type.update',$item->id) }}" method="POST">
            @csrf
            @method('PUT')

            @include('SelectedOptions.leaf_type._form', ['item' => $item])

            <button class="btn btn-success">Update</button>
            <a href="{{ route('leaf-type.index') }}" class="btn btn-secondary">
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
