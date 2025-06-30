@extends("layouts.Master")
@section("main_section")
<style>
.modal-backdrop.show, .show.blockOverlay {
    opacity: 0 !important;
    display: none;
}


.modal.show {
    display: block !important;
    z-index: 1050 !important;    /* ensure modal is above backdrop */
}

#certificationModal .modal-dialog {
    top: 10%; /* pushes modal down from top */
    z-index: 1060 !important;
}
</style>
<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div>Assign Plot Ref/Certification No</div>
                    </div>
                    <div class="col-sm-6 ">
                        @if(Auth::user()->UserType=='2' || Auth::user()->UserType=='3')
                            <a href="{{url('order/generate')}}/{{Request::segment(3)}}"
                                class="btn-shadow btn btn-info float-right" style="margin-right:5px">
                                <i class="fa fa-arrow-left"></i> Back
                            </a>
                            <input type="hidden" id="qId" value="{{Request::segment(3)}}">
                        @endif
                    </div>
                </div>
                <hr>
                <table style="width: 100% !important;" id="example" class="table table-hover  table-bordered">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Screen Type</th>
                            <th>FireRating</th>
                            <th>Glazing Type</th>
                            <th>Plot Ref No</th>
                            <th>Certification No </th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {!! $tbl !!}
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    <button style="display: none;" type="button" id="success-alert" data-type="success"
        class="btn btn-success btn-show-swal"></button>

    <input type="hidden" id="url" value="{{ url('quotation/door-list-delete') }}">
    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" />
</div>
<div class="modal fade" id="certificationModal" tabindex="-1" aria-labelledby="certificationModalLabel" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('certification.store') }}">
            @csrf
            <input type="hidden" id="modal_itemId" name="item_id">
            <input type="hidden" id="modal_itemmasterId" name="itemmaster_id">
            <input type="hidden" id="modal_vid" name="vid">
            <input type="hidden" id="type" name="sidescreen" value="yes">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Certification</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Plot Ref No</label>
                        <input type="text" class="form-control" name="plot_ref_no" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Certification No</label>
                        <input type="text" class="form-control" name="certification_no" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-backdrop fade show" style="background-color: #fff !important; opacity: 0.8 !important; display: block !important;"></div>
</div>


    @endsection


    @section('js')
    @if(session()->has('updated'))
    <script type="text/javascript">
    swal(
        'Success',
        'User Updated the <b style="color:green;">Success</b>!',
        'success'
    )
    </script>
    @endif

    @if(session()->has('added'))
    <script type="text/javascript">
    swal(
        'Success',
        'Quotation Added <b style="color:green;">Success</b>!',
        'success'
    )
    </script>
    @endif

    <script>
       function doorListAjax(itemId, itemmasterId = '', vid) {
            $('#modal_itemId').val(itemId);
            $('#modal_itemmasterId').val(itemmasterId);
            $('#modal_vid').val(vid);
            const myModal = new bootstrap.Modal(document.getElementById('certificationModal'));
            myModal.show();
        }
    </script>
    @endsection
