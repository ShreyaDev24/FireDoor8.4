@extends('layouts.Master')

@section('main_section')
<div class="container">
    <h4>Glass Certificates</h4>

    <a href="{{ route('glass-certificates.create') }}" class="btn btn-primary mb-3">
        Add Certificate
    </a>

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

    {{ $certificates->links() }}
</div>
@endsection
