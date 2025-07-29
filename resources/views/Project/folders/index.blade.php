<a href="#" class="btn btn-primary">Add Folder</a>
<table>
    <thead>
        <tr>
            <th>Folder Name</th>
            <th>Ironmongery Set Count</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($folders as $folder)
        <tr>
            <td>{{ $folder->name }}</td>
            <td>{{ $folder->ironmongery_sets_count }}</td>
            <td>
                <a href="{{ route('folders.show', $folder->id) }}" class="btn btn-info">View</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
