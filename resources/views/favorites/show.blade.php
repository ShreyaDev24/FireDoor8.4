@extends('layouts.Master')

@section('main_section')
    <div class="container">
        <h2>Favourite Details</h2>
        <p><strong>Name:</strong> {{ $favorite->name }}</p>
        <p><strong>User:</strong> {{ $favorite->user->UserEmail ?? 'N/A' }}</p>
        <p><strong>Status:</strong> {{ ($favorite->status == 1)?'Active':'Inactive' }}</p>
        <br>
        <div
            style="background: #fff3cd; color: #856404; padding: 10px 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #ffeeba;">
            <strong>Note:</strong> If you edit a Door or Screen here, it will reflect in its quotation
            where you select the door or screen for favorite. Please make changes accordingly if you want them to apply in
            the quotation.
        </div>
        <hr>
        <div class="table-responsive">
            {!! $html !!}
        </div>
        <br>
        <hr>
        <div class="table-responsive">
            {!! $htmlScreen !!}
        </div>

    </div>
    <input type="hidden" id="favoriteDeleteItem" value="{{ url('/quotation/favoriteDeleteItem') }}" />
    <input type="hidden" id="favadjustPriceUrl" value="{{ url('/quotation/fav-adjustPriceUrl') }}" />
    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" />
@endsection
@section('js')
    <script>
        function adjustPrice(favId, id, totalPrice,quotationId,versionId,ItemId) {
            $('#adjustPriceitemId').val(favId);
            $('#adjustPriceitemMasterId').val(id);
            $('#totalPrice').val(totalPrice);
            $('#quotationId').val(quotationId);
            $('#versionId').val(versionId);
            $('#ItemId').val(ItemId);
            $("#adjust-price-modal").modal("show");
        }
        function favoriteDeleteItem(id) {
            $('.loader').empty().css({
                'display': 'block'
            });
            var r = confirm("Are you sure! you wan't to delete it.");
            if (r == true) {
                $.ajax({
                    url: $("#favoriteDeleteItem").val(),
                    method: "POST",
                    data: {
                        _token: $("#_token").val(),
                        id: id
                    },
                    dataType: "Json",
                    success: function(data) {
                        if (data.status == true) {
                            location.reload();
                        } else {
                            swal('error', data.msg, 'error').then(function() {
                                location.reload();
                            });
                        }
                    }
                });
            }
        }

        function adjustPriceAjax() {
                $('.loader').empty().css({
                    'display': 'block'
                });
                var favId = $('#adjustPriceitemId').val();
                var itemMasterId = $('#adjustPriceitemMasterId').val();
                var AdjustPrice = $('#AdjustPrice').val();
                var totalPrice = $('#totalPrice').val();
                var versionId = $('#versionId').val();
                var quotationId = $('#quotationId').val();
                var ItemId = $('#ItemId').val();
                //if (parseFloat(AdjustPrice) < parseFloat(totalPrice)) {
                    $.ajax({
                        url: $("#favadjustPriceUrl").val(),
                        method: "POST",
                        data: {
                            _token: $("#_token").val(),
                            favId: favId,
                            itemMasterId: itemMasterId,
                            AdjustPrice: AdjustPrice,
                            quotationId: quotationId,
                            versionId: versionId,
                            ItemId: ItemId,
                        },
                        dataType: "Json",
                        success: function(data) {
                            if (data.status == true) {
                                swal('success', data.msg, 'success').then(function() {
                                    location.reload();
                                });
                            } else {
                                swal('error', data.msg, 'error').then(function() {
                                    location.reload();
                                });
                            }
                        }
                    });
                //} else {
                //    $('.loader').empty().css({
                  //      'display': 'none'
                    //});
                   // alert("Adjust price can't be greater than total price!");
                   // return false;
               // }
            }
    </script>
@endsection

  {{--  //adjust price modal  --}}
    <div id="adjust-price-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Adjust Price</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 mb-2">
                            <label for="doorTypeName">Total Price</label>
                            <input type="number" class="form-control" id="totalPrice" readonly>
                        </div>
                        <div class="col-sm-12 mb-2">
                            <label for="doorTypeName">Adjust Price</label>
                            <input type="number" class="form-control" id="AdjustPrice"
                                pattern="[0-9]+([\.,][0-9]+)?" placeholder="Enter Adjust Price" required=""
                                step="0.01">
                            <input type="hidden" class="form-control" id="adjustPriceitemId">
                            <input type="hidden" class="form-control" id="adjustPriceitemMasterId">
                            <input type="hidden" class="form-control" id="quotationId">
                            <input type="hidden" class="form-control" id="versionId">
                            <input type="hidden" class="form-control" id="ItemId">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button onclick="adjustPriceAjax()" class="btn btn-success">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
