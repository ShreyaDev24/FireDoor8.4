{{-- Shared: open selected Ironmongery edit page in a new tab (outside #itemForm) --}}
@once
<form action="{{ route('updAddIronmongery') }}"
      method="POST"
      id="openIronmongeryEdit"
      target="_blank">
    @csrf
    <input type="hidden"
           name="updAddIronmongery"
           id="selectedIronmongeryEditId">
</form>
<script>
window.addEventListener('load', function () {
    if (window.__editSelectedIronmongeryBound) {
        return;
    }
    window.__editSelectedIronmongeryBound = true;

    if (typeof jQuery === 'undefined') {
        return;
    }

    jQuery(document).on('click', '.editSelectedIronmongery', function () {
        var selectedId = jQuery('#IronmongeryID').val();

        if (!selectedId) {
            alert('Please select an Ironmongery Set first.');
            return;
        }

        var $editForm = jQuery('#openIronmongeryEdit');
        if (!$editForm.length) {
            return;
        }

        jQuery('#selectedIronmongeryEditId').val(selectedId);
        $editForm.submit();
    });
});
</script>
@endonce
