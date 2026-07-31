@extends("layouts.Master")

@section("main_section")
<style>
    .action-icon {
        font-size: 14px;
        cursor: pointer;
        transition: 0.2s ease;
    }

    .action-icon:hover {
        transform: scale(1.15);
        opacity: 0.8;
    }
</style>
<div class="app-main__outer">
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible float-right">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h5><i class="fas fa-skull-crossbones"></i> Alert!</h5>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    @if(session()->has('success'))
    <div class="alert alert-success alert-dismissible float-right">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h5><i class="icon fas fa-check"></i> Alert!</h5>
        {{ session()->get('success') }}
    </div>
    @endif
    @if(session()->has('error'))
    <div class="alert alert-danger alert-dismissible float-right">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h5><i class="fas fa-skull-crossbones"></i> Alert!</h5>
        {{ session()->get('error') }}
    </div>
    @endif

    <div class="app-main__inner">

        <div class="d-flex justify-content-between mb-3">
            <h4>Lock Type</h4>
            <a href="{{ route('Lock-Type.create') }}" class="btn btn-primary">
                + Add New
            </a>
        </div>

        <ul class="nav nav-tabs mb-3" id="lockTypeTabs" role="tablist">
            @foreach ($brands as $name => $value)
            <li class="nav-item">
                <a class="nav-link @if($loop->first) active @endif" data-toggle="tab" href="#{{ $name }}">{{ $name }}</a>
            </li>
            @endforeach
        </ul>

        <div class="tab-content">
            @foreach ($brands as $name => $value)
            <div class="tab-pane fade @if($loop->first) show active @endif" id="{{ $name }}">
                <table class="table table-bordered table-hover {{ $name }}Table">
                    <thead>
                        <tr>
                            <th class="text-center" style="width:120px;">{{ $name }}</th>
                            <th>Lock Type</th>
                            <th class="text-center" style="width:120px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            @if($item->configurableitems == $value)
                            <tr>
                                <td class="text-center"><i class="fa fa-check text-success"></i></td>
                                <td><b>{{ $item->OptionValue }}</b></td>

                                @if($item->editBy != 1 || $auth->UserType == 1)
                                <td class="text-center">
                                    <div class="d-flex justify-content-center align-items-center gap-2">

                                        {{-- Edit --}}
                                        <a href="{{ route('Lock-Type.edit', $item->id) }}"
                                            class="action-icon text-success"
                                            title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        {{-- Delete --}}
                                        <form action="{{ route('Lock-Type.destroy', $item->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure?')"
                                            class="m-0 p-0">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="action-icon text-danger border-0 bg-transparent"
                                                title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                                @else
                                <td></td>
                                @endif
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
$(document).ready(function () {
    @foreach ($brands as $name => $value)
    $('.{{ $name }}Table').DataTable({
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        ordering: true,
        searching: true,
        responsive: true,
        autoWidth: false,
        columnDefs: [
            { width: '120px', className: 'text-center', targets: [0, -1] },
            { orderable: false, targets: [-1] }
        ]
    });
    @endforeach
});
</script>
@endsection
