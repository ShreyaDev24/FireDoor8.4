@extends("layouts.Master")

@section("main_section")
<script>
    function Tooltip(tooltipValue) {
        let TooltipCode2 =
            `<i class="fa fa-info-circle field_info tooltip" aria-hidden="true">
                <span class="tooltiptext info_tooltip">` + tooltipValue + `</span>
            </i>`;
        return TooltipCode2;
    }
</script>
<div class="app-main__outer">
    <div class="app-main__inner">

        @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-check"></i> Alert!</h5>
            {{ session()->get('success') }}
        </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="fas fa-skull-crossbones"></i> Alert!</h5>
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="tab-content mb-5" id="ironmongery" data-tab-content>
            <div class="row">
                <div class="col-sm-12">
                    <div class="custom_card">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="card-header"><h5 class="card-title">Ironmongery <span>Set</span></h5></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <h3>Folder: {{ $folder->name }}</h3>

                                <ul>
                                @foreach ($sets as $set)
                                    <li>{{ $set->Setname }}</li>
                                @endforeach
                                </ul>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection




