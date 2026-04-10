@extends('layouts.Master')

@section('main_section')
<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="card-header">
                            <h5 class="card-title">Glass Certificates</h5>
                        </div>
                    </div>
                    <div class="col-sm-6 ">
                        <a href="{{ route('glass-certificates.create') }}"  class="btn-shadow btn btn-info float-right">Add Certificate</a>
                    </div>
                </div>
                <hr>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <table class="table table-bordered" id="certificateTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Brand Of Core</th>
                            <th>Glass Type</th>
                            <th>Glass Thickness</th>
                            <th>Reference</th>
                            <th>Fire Rating</th>
                            <th>Document</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($certificates as $certificate)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ configurationDoor($certificate->brand_of_core) }}</td>
                            <td>{{ $certificate->glassType->GlassType ?? '-' }}</td>
                            <td>{{ $certificate->glass_thickness }}</td>
                            <td>{{ $certificate->certificate_reference }}</td>
                            <td>{{ $certificate->fire_rating }}</td>
                            <td>
                                @if(!empty($certificate->document_path))
                                    <div class="d-flex align-items-center mb-2 p-2 border rounded bg-light">
                                        <i class="fa fa-file-pdf-o text-danger fs-4 me-2"></i>

                                        <div class="flex-grow-1">
                                            <strong>PDF</strong>
                                        </div>

                                        <a href="{{ asset($certificate->document_path) }}"
                                        target="_blank"
                                        class="btn btn-sm btn-outline-primary">
                                            View
                                        </a>
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if($certificate->user_id != 1 || Auth::user()->UserType == 1)
                                <a href="{{ route('glass-certificates.edit', $certificate) }}" class="btn btn-sm btn-warning">Edit</a>

                                <form action="{{ route('glass-certificates.destroy', $certificate) }}"
                                    method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger"
                                            onclick="return confirm('Delete?')">
                                        Delete
                                    </button>
                                </form>
                                @endif
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    $(document).ready(function () {

        let table = $('#certificateTable').DataTable({
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            ordering: true,
            searching: true,
            responsive: true,
            columnDefs: [
                { orderable: false, targets: [0, -1] }
            ]
        });

    });
</script>
@endsection
