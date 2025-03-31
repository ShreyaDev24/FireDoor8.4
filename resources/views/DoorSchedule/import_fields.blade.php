
@extends("layouts.Master")
@section("main_section")
<div class="app-main__outer">
<div class="app-main__inner">
<div class="tab-content">
<div class="main-card mb-3 card">
    <div class="card-body">
        <div class="card-header">
            <h5 class="card-title" style="margin-top: 10px">Add New Doors [With Match Column]</h5>
        </div>
        <p style="color:red; font-size:12px; margin-top:5px">Selection of firerating, doornumber and doortype is must. Project floor name should match document floor name.</p>
        <div class="row">
            <div class="col-sm-4">
                <table id="example3" class="table table-striped table-bordered">
                    @foreach ($csv_data[0][0] as $key => $row)
                        <tr>
                            <td>{{ $row }}</td>
                            <td class="tblColumn">
                                <input type="hidden" class="columnName" value="fields{{$key}}[]">
                                <input type="hidden" class="index" value="{{$key}}">
                                <select class="form-control DBfields"></select>
                            </td>
                        </tr>
                    @endforeach
                </table>
                <button type="submit" id="submit" class="btn btn-primary">Import Data</button>
            </div>
            <div class="col-sm-12">
                <form class="form-horizontal" id="formSubmit" method="POST" action="{{ route('import_process') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="quotationId" value="{{$quotationId}}">
                    <input type="hidden" name="versionId" value="{{$vid}}">
                    <input type="hidden" name="pageType" value="{{ $ConfigurationType }}">
                    <div class="table-responsive" style="height:0">
                    <table id="example" class="table table-striped table-bordered">
                        @foreach ($csv_data[0] as $k => $row)
                            @if($k > 0)
                            <tr>
                            @foreach ($row as $key => $value)
                                <td><input type="text" name="fields{{$key}}[]" value="{{$value}}"></td>
                            @endforeach
                            </tr>
                            @endif
                        @endforeach
                    </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>

@endsection
@section("script_section")
<script>
    $(document).ready(function(){
        // $("#example").hide();
        $("#example").css({'visibility':'hidden'});

        let db_fields = ['Not Selected', 'DoorNumber','DoorType','FireRating','SOWidth','SOHeight','sODepth','Floor','doorsetType','SwingType','LatchType','Handing','Tollerance','GAP','FrameMaterial','FrameType','PlantonStopWidth','PlantonStopHeight','RebatedWidth','RebatedHeight','FrameDepth','LippingType','LippingThickness','LippingSpecies','Accoustics','rWdBRating','Architrave','architraveWidth','architraveThickness','ArchitraveMaterial','ArchitraveType','ArchitraveFinish','ArchitraveFinishColor','ArchitraveSetQty','FrameThickness'];
        let column = '';
        db_fields.map(function(value,key){
            column += `<option value="`+key+`">`+value+`</option>`;
        });
        $('.DBfields').html(column);
        $(document).on('change','.DBfields',function(){
            let val = $(this).val();
            let input = $(this).siblings('.index').val();
            let ArrayColumnName = $(this).siblings('.columnName').val();
            if(val > 0){
                let mm = $(this).find(".theText").val();
                $("select option[value='"+ mm + "']").attr('disabled', false);
                $("select option[value='"+ mm + "']").removeAttr('style');
                $(this).find('.theText').removeClass('theText');
                $(this).find('option:selected').addClass('theText');
                $("select option[value='"+ val + "']").attr('disabled', true);
                $("select option[value='"+ val + "']").css({'background':'#ecebeb'});

                // $('input[name="fields'+input+'[]"]').addClass('ioi');
                let $columnName = '';
                if(val == 1){
                    $columnName = 'DoorNumber[]';
                } else if(val == 2){
                    $columnName = 'DoorType[]';
                } else if(val == 3){
                    $columnName = 'FireRating[]';
                } else if(val == 4){
                    $columnName = 'SOWidth[]';
                } else if(val == 5){
                    $columnName = 'SOHeight[]';
                } else if(val == 6){
                    $columnName = 'sODepth[]';
                //} else if(val == 8){
                //    $columnName = 'DoorLeafFacing[]';
                //} else if(val == 9){
                //    $columnName = 'Leaf1VisionPanel[]';
                //} else if(val == 10){
                //    $columnName = 'vP1Width[]';
                //} else if(val == 11){
                 //   $columnName = 'vP1Height1[]';
                } else if(val == 7){
                    $columnName = 'Floor[]';
                } else if(val == 8){
                    $columnName = 'doorsetType[]';
                } else if(val == 9){
                    $columnName = 'SwingType[]';
                } else if(val == 10){
                    $columnName = 'LatchType[]';
                } else if(val == 11){
                    $columnName = 'Handing[]';
                } else if(val == 12){
                    $columnName = 'Tollerance[]';
                } else if(val == 13){
                    $columnName = 'GAP[]';
                } else if(val == 14){
                    $columnName = 'FrameMaterial[]';
                } else if(val == 15){
                    $columnName = 'FrameType[]';
                } else if(val == 16){
                    $columnName = 'PlantonStopWidth[]';
                } else if(val == 17){
                    $columnName = 'PlantonStopHeight[]';
                } else if(val == 18){
                    $columnName = 'RebatedWidth[]';
                } else if(val == 19){
                    $columnName = 'RebatedHeight[]';
                } else if(val == 20){
                    $columnName = 'FrameDepth[]';
                } else if(val == 21){
                    $columnName = 'LippingType[]';
                } else if(val == 22){
                    $columnName = 'LippingThickness[]';
                } else if(val == 23){
                    $columnName = 'LippingSpecies[]';
                } else if(val == 24){
                    $columnName = 'Accoustics[]';
                } else if(val == 25){
                    $columnName = 'rWdBRating[]';
                } else if(val == 26){
                    $columnName = 'Architrave[]';
                } else if(val == 27){
                    $columnName = 'architraveWidth[]';
                } else if(val == 28){
                    $columnName = 'architraveThickness[]';
                } else if(val == 29){
                    $columnName = 'ArchitraveMaterial[]';
                } else if(val == 30){
                    $columnName = 'ArchitraveType[]';
                } else if(val == 31){
                    $columnName = 'ArchitraveFinish[]';
                } else if(val == 32){
                    $columnName = 'ArchitraveFinishColor[]';
                } else if(val == 33){
                    $columnName = 'ArchitraveSetQty[]';
                } else if(val == 34){
                    $columnName = 'FrameThickness[]';
                }
                $(this).siblings('.columnName').val($columnName);
                $('input[name="'+ArrayColumnName+'"]').attr('name',$columnName);
            } else {
                let mm = $(this).find(".theText").val();
                $("select option[value='"+ mm + "']").attr('disabled', false);
                $("select option[value='"+ mm + "']").removeAttr('style');

                $(this).siblings('.columnName').val("fields"+input+"[]");
                $('input[name="'+ArrayColumnName+'"]').attr('name',"fields"+input+"[]");
            }
        })
        $(document).on('click','#submit',function(){
            $('#formSubmit').submit();
        })
    })
</script>
@endsection



