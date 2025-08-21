@extends('layouts.Master')

@section('main_section')
<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="card-header">
                            <h5 class="card-title">Core Certificates</h5>
                        </div>
                    </div>
                    <div class="col-sm-6 ">
                        <a href="{{ route('core_certificates.create') }}"  class="btn-shadow btn btn-info float-right">Add New</a>
                    </div>
                </div>
                <hr>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Brand</th>
                            <th>Fire Rating</th>
                            <th>Reference</th>
                            <th>Expiry Date</th>
                            <th>Document</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($certificates as $certificate)
                            <tr>
                                <td>{{ $certificate->name }}</td>
                                <td>{{ $certificate->fire_rating }}</td>
                                <td>{{ $certificate->test_certificate_reference }}</td>
                                <td>{{ $certificate->expiry_date }}</td>
                                <td>
                                    @if($certificate->document_path)
                                        <a href="{{ asset('/' . $certificate->document_path) }}" target="_blank">View</a>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('core_certificates.edit', $certificate) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('core_certificates.destroy', $certificate) }}" method="POST" style="display:inline-block;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Delete this certificate?')">Delete</button>
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
@endsection
