
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
                    <div class="table-responsive" style="height:0">
                    <table id="example" class="table table-striped table-bordered">
                        <input type="hidden" name="pageType" value="{{ $ConfigurationType }}">

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

        let db_fields = ['Not Selected', 'Floor','Location','DoorNumber','DoorDescription','LeafWidth1','LeafWidth2','LeafHeight','sODepth','DoorType','LeafType','DoorLeafFacing','Handing','OpensInwards','VpSize1','VpSize2','GlassType','FrameMaterials','FrameFinish', 'ArchitraveMaterial','ArchitraveSetQty','IronSet','rWDBRating','FireRating'];
        //   let db_fields = ['Not Selected', 'DoorNumber','DoorType','FireRating','doorsetType','SOWidth','SOHeight','sODepth','DoorLeafFacing','Leaf1VisionPanel','vP1Width','vP1Height1','Floor','Architrave','architraveWidth','architraveThickness', 'DoorDescription', 'LeafWidth1','LeafWidth2','LeafHeight','LeafType','Handing','OpensInwards','VpSize1','VpSize2','GlassType','FrameMaterials','FrameFinish', 'ArchitraveMaterial','ArchitraveSetQty','IronSet','rWDBRating','Location'];
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
                    $columnName = 'Floor[]';
                } else if(val == 2){
                    $columnName = 'Location[]';
                } else if(val == 3){
                    $columnName = 'DoorNumber[]';
                } else if(val == 4){
                    $columnName = 'DoorDescription[]';
                } else if(val == 5){
                    $columnName = 'LeafWidth1[]';
                } else if(val == 6){
                    $columnName = 'LeafWidth2[]';
                } else if(val == 7){
                    $columnName = 'LeafHeight[]';
                } else if(val == 8){
                    $columnName = 'sODepth[]';
                } else if(val == 9){
                    $columnName = 'DoorType[]';
                } else if(val == 10){
                    $columnName = 'LeafType[]';
                } else if(val == 11){
                    $columnName = 'DoorLeafFacing[]';
                } else if(val == 12){
                    $columnName = 'Handing[]';
                } else if(val == 13){
                    $columnName = 'OpensInwards[]';
                } else if(val == 14){
                    $columnName = 'VpSize1[]';
                } else if(val == 15){
                    $columnName = 'VpSize2[]';
                } else if(val == 16){
                    $columnName = 'GlassType[]';
                } else if(val == 17){
                    $columnName = 'FrameMaterials[]';
                }else if(val == 18){
                    $columnName = 'FrameFinish[]';
                }else if(val == 19){
                    $columnName = 'ArchitraveMaterial[]';
                }else if(val == 20){
                    $columnName = 'ArchitraveSetQty[]';
                }else if(val == 21){
                    $columnName = 'IronSet[]';
                }else if(val == 22){
                    $columnName = 'rWDBRating[]';
                }else if(val == 23){
                    $columnName = 'FireRating[]';
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



