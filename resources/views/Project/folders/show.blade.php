<h3>Folder: {{ $folder->name }}</h3>

<ul>
@foreach ($sets as $set)
    <li>{{ $set->name }}</li>
@endforeach
</ul>
