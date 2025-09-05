@extends("layouts.Master")

@section("main_section")
<div class="app-main__outer">
    <div class="app-main__inner">
        @if(session()->has('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-check"></i> Alert!</h5>
                {!! session()->get('success') !!}
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
        <form action="{{ route('folders.store') }}" method="POST">
            @csrf
            <div class="tab-content">
                <div class="main-card mb-3 card">
                    <div class="">
                        <div class="tab-content">
                            <div class="card-header">
                                <h5 class="card-title" style="margin-top: 10px">Create Folder</h5>
                            </div>
                            <div class="">
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="main-card mb-3">
                                            <div class="card-body">
                                                <div class="col-md-12 p-0">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Folder Name <span class="text-danger">*</span></label>
                                                                <input class="form-control" type="text" name="foldername" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="ironmongery_sets">Select Ironmongery Sets<span class="text-danger">*</span></label>
                                                                <select required name="ironmongery_sets[]" id="ironmongery_sets" multiple class="form-control selectpicker">
                                                                    @foreach($sets as $rr)
                                                                    <option value="{{ $rr->id }}">{{ $rr->Setname }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="main-card mb-3 custom_card">
                    <div class="d-block text-right">
                        <button type="submit" id="submit" class="btn-wide btn btn-success">
                                Create Now
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
