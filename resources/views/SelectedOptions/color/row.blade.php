@php
$inch = '';
if(!empty($item->inch_width) && !empty($item->inch_height)){
    $inch = $item->inch_width .' x '.$item->inch_height;
}
@endphp
<tr>
    @if($auth->id != 1)
    <td>
        <input type="checkbox" class="rowCheck" value="{{ $item->id }}" {{ $item->selectedId ? 'checked'
        :
        '' }}>
    </td>
    @endif
    <td>{{ $item->ColorName }}</td>
    <td class="colorfill" style="background-color: {{ $item->Hex }}">
        {{ $item->Hex }}
    </td>
    <td>{{ $item->DoorLeafFacing }}</td>
    <td>{{ $item->DoorLeafFacingValue }}</td>

    @if($item->editBy != 1 || Auth::user()->UserType == 1)

    <td class="text-center">
        <div class="d-flex justify-content-center align-items-center gap-2">

            {{-- Edit --}}
            <a href="{{ route('Colour-List.edit', $item->id) }}"
            class="action-icon text-success"
            title="Edit">
                <i class="fa fa-edit"></i>
            </a>

            {{-- Delete --}}
            <form action="{{ route('Colour-List.destroy', $item->id) }}"
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

    @if($auth->id != 1 && $item->SelectedPrice !== null)
    <td style="min-width: 80px;">
        <input type="number"
            step="0.01"
            class="form-control priceInput"
            value="{{ number_format($item->SelectedPrice, 2, '.', '') }}"
            data-option-id="{{ $item->id }}"
            data-selected-id="{{ $item->selectedId }}"
            data-option-type="color_list"
            onkeyup="chooseOptionCost(this)">
    </td>
    @elseif($auth->id != 1)
    <td></td>
    @endif
</tr>
