@extends("layouts.Master")

@section("main_section")
<div class="app-main__outer">

    <div class="app-main__inner">

        <h4 class="mb-3">Edit Glass Type</h4>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <form action="{{ route('Glass-type.update',$item->id) }}" method="POST"  enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @include('SelectedOptions.glass_type._formStandard', ['item' => $item])

            <button class="btn btn-success">Update</button>
            <a href="{{ route('Glass-type.index') }}" class="btn btn-secondary">
                Cancel
            </a>
        </form>

    </div>
</div>
@endsection
@section('js')
<script>
    const optionsslug = 'leaf1_glazing_beads';

    function getSelectedConfigurations() {
        let selected = [];
        $('.core-checkbox:checked').each(function () {
            selected.push($(this).val());
        });
        return selected;
    }

    function loadGlazingBeads() {
        let selectedConfigurations = getSelectedConfigurations();

        if (!selectedConfigurations.length) {
            $('#glazingBeadsContainer').html(
                '<small class="text-muted">Select configuration to load glazing beads</small>'
            );
            return;
        }

        $.ajax({
            url: '/options/get-glazing-beads',
            method: 'GET',
            data: {
                configurations: selectedConfigurations,
                optionsslug: optionsslug
            },
            success: function (response) {
                const $container = $('#glazingBeadsContainer');
                $container.empty();

                let beadsResponse = response.data ?? response;
                let selectedBeads = Array.isArray(window.savedGlazingBeads)
                    ? window.savedGlazingBeads.map(v => v.toLowerCase().trim())
                    : [];

                beadsResponse.forEach(bead => {
                    const key = String(bead.OptionKey || '').trim().toLowerCase();
                    const value = bead.OptionValue || key;

                    const checked = selectedBeads.includes(key) ? 'checked' : '';

                    $container.append(`
                        <label class="d-block">
                            <input type="checkbox"
                                name="GlazingBeads[]"
                                value="${key}"
                                class="ml-2 option-style"
                                ${checked}>
                            ${value}
                        </label>
                    `);
                });

            },
            error: function (xhr) {
                console.error('Glazing beads load failed', xhr.responseText);
            }
        });
    }

    // Load on edit page
    $(document).ready(function () {
        setTimeout(loadGlazingBeads, 200);
    });

    // Reload when core changes
    $(document).on('change', '.core-checkbox', function () {
        loadGlazingBeads();
    });
</script>

@endsection
