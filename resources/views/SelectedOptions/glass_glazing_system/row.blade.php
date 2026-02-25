<tr>
    <td class="text-center"><i class="fa fa-check text-success"></i></td>
    <td class="text-center">{!! yesNoIcon($item->NFR) !!}</td>
    <td class="text-center">{!! yesNoIcon($item->FD30) !!}</td>
    <td class="text-center">{!! yesNoIcon($item->FD60) !!}</td>
    <td>{{ $item->GlassType }}</td>
    <td>{{ $item->GlazingSystem }}</td>
    <td>{{ $item->VPAreaSize }}</td>

    <td class="text-center">
        @if($item->UserId != 1 || auth()->user()->UserType == 1)
            <a href="{{ route('Glass-Glazing-System.edit', $item->mainId) }}" class="text-success me-2">
                <i class="fa fa-edit"></i>
            </a>

            <form action="{{ route('Glass-Glazing-System.destroy', $item->mainId) }}" method="POST" class="d-inline"
                  onsubmit="return confirm('Are you sure?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-link p-0 text-danger">
                    <i class="fa fa-trash"></i>
                </button>
            </form>
        @endif
    </td>
</tr>
