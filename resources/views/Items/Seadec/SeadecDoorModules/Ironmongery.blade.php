<!-- Ironmongery -->
<div class="main-card mb-3 custom_card">
    <div>
        <div class="tab-content">
            <div class="card-header">
                <h5 class="card-title" style="margin-top: 10px">Ironmongery</h5>
            </div>
            <div>
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="ironmongerySet">Ironmongery Set
                            @if(!empty($tooltip->ironmongerySet))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->ironmongerySet}}'));
                            </script>
                            @endif
                            </label>
                            <label for="ironmogery_set" style="display: none;">Ironmongery Set</label>
                            <select name="ironmongerySet" id="ironmongerySet" class="form-control">
                                <option value="Yes" @if(isset($Item['IronmongerySet'])) @if($Item['IronmongerySet'] == "Yes") {{'selected'}} @endif @endif>Yes</option>
                                <option value="No" @if(isset($Item['IronmongerySet']))
                                    @if($Item['IronmongerySet'] == "No")
                                        {{'selected'}}
                                    @endif
                                    @else {{'selected'}} @endif>No</option>
                            </select>
                        </div>
                    </div>
                    <!-- Folder Dropdown -->
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="folderDropdown">Select Folder</label>
                            <select id="folder_id" class="form-control" name="FolderId">
                                <option value="">Select Folder</option>
                                @foreach($folders as $folderId => $ironmongeries)
                                    <option value="{{ $folderId }}" @if(isset($Item['FolderId'])) @if($Item['FolderId'] == $folderId) {{'selected'}} @endif @endif >{{ $ironmongeries->first()->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6" id="ironmongeryWrapper" style="display: none;">
                        <div class="position-relative form-group">
                            <label for="IronmongeryID">Select Ironmongery Set
                            @if(!empty($tooltip->selectironmongerySet))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->selectironmongerySet}}'));
                            </script>
                            @endif
                            </label>
                            <label for="select_ironmogery_set" style="display: none;">Select Ironmongery Set</label>
                            <select name="IronmongeryID" id="IronmongeryID" class="form-control" @if(empty($Item['IronmongerySet']) || $Item['IronmongerySet'] == "No") {{'disabled'}} @endif>
                                <option value="">Select Ironmongery Set</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="ironmongeryid" value="{{ isset($Item['IronmongeryID']) ? $Item['IronmongeryID'] : '' }}">
<script>
var folderIronmongeryMap = @json($folders);
</script>
