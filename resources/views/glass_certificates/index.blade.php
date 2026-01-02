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

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Glass Type</th>
                            <th>Reference</th>
                            <th>Fire Rating</th>
                            <th>Expiry</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($certificates as $certificate)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $certificate->glassType->GlassType ?? '-' }}</td>
                            <td>{{ $certificate->certificate_reference }}</td>
                            <td>{{ $certificate->fire_rating }}</td>
                            <td>{{ $certificate->expiry_date }}</td>
                            <td>
                                <a href="{{ route('glass-certificates.edit', $certificate) }}" class="btn btn-sm btn-warning">Edit</a>

                                <form action="{{ route('glass-certificates.destroy', $certificate) }}"
                                    method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger"
                                            onclick="return confirm('Delete?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
{{ $certificates->links() }}
@endsection
