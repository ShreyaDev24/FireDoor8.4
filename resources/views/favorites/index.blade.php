@extends('layouts.Master')

@section('main_section')
<div class="app-main__outer">

    <div class="app-main__inner">

        <div class="main-card mb-3 card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="card-header">
                            <h5 class="card-title">Favourite List</h5>
                        </div>
                    </div>
                    <div class="col-sm-6 ">
                        <a href="{{ route('favorites.create') }}"  class="btn-shadow btn btn-info float-right">Add New</a>
                    </div>
                </div>
                <hr>

                <div class="table-responsive">
                    <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered">
                        <thead class="text-uppercase table-header-bg">
                            <tr class="text-white">
                                <th>Sr No</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($favorites as $index => $favorite)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $favorite->name }}</td>
                                <td>
                                    @if($favorite->status)
                                    <span class="badge bg-success">Active</span>
                                    @else
                                    <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $favorite->created_at->format('d M Y') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('favorites.show', $favorite->id) }}" class="btn btn-sm btn-info"
                                        title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('favorites.edit', $favorite->id) }}"
                                        class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('favorites.destroy', $favorite->id) }}" method="POST"
                                        class="d-inline-block" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No favorites found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
