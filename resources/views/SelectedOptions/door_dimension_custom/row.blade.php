@php
$inch = '';
if(!empty($item->inch_width) && !empty($item->inch_height)){
$inch = $item->inch_width .' x '.$item->inch_height;
}
@endphp
<tr>
    @if($auth->id != 1)
    <td>
        <input type="checkbox" class="rowCheck" value="{{ $item->id }}" {{ $item->selectedPrice?->id ? 'checked'
        :
        '' }}>
    </td>
    @endif
    <td class="text-center"><i class="fa fa-check text-success"></i></td>
    <td>{{ $item->fire_rating }}</td>
    <td>{{ $item->mm_width }} x {{ $item->mm_height }}</td>

    @if($item->editBy != 1 || Auth::user()->UserType == 1)

    <td class="text-center">
        <div class="d-flex justify-content-center align-items-center gap-2">

            {{-- Edit --}}
            <a href="{{ route('Door-Dimension-Custom.edit', $item->id) }}" class="action-icon text-success"
                title="Edit">
                <i class="fa fa-edit"></i>
            </a>

            {{-- Delete --}}
            <form action="{{ route('Door-Dimension-Custom.destroy', $item->id) }}" method="POST"
                onsubmit="return confirm('Are you sure?')" class="m-0 p-0">
                @csrf
                @method('DELETE')

                <button type="submit" class="action-icon text-danger border-0 bg-transparent" title="Delete">
                    <i class="fa fa-trash"></i>
                </button>
            </form>

        </div>
    </td>

    @else
    <td></td>
    @endif

    @php
    $isChecked = $item->selectedPrice ? true : false;

    $costs = [];
    if(!$isChecked && $item->selectedPrice?->custome_door_selected_cost){
    $costs = $item->selectedPrice->custome_door_selected_cost;
    }
    @endphp

    @if($auth->id != 1 && $item->selectedPrice?->id)

    @php
    $costs = $item->selectedPrice->custome_door_selected_cost ?? [];
    @endphp

    <td class="price-column">
        <div class="price-grid">
            @foreach ($item->leafTypes as $leaf)
                @php
                    $costValue = $item->selectedPrice->custome_door_selected_cost[$leaf->id] ?? '';
                @endphp

                <div class="price-item">
                    <span>{{ $leaf->leaf_type_key }}</span>

                    <input type="number"
                        value="{{ $costValue }}"
                        class="price-input cost-input"
                        data-optionid="{{ $item->id }}"
                        data-selectedid="{{ $item->selectedPrice->id ?? '' }}"
                        data-leaftypeid="{{ $leaf->id }}"
                        step="0.01">
                </div>
            @endforeach
        </div>
    </td>

    @elseif($auth->id != 1)
    <td></td>
    @endif
</tr>
