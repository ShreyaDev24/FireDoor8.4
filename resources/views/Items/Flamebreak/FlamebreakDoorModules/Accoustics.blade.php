                        <!-- ACCOUSTICS -->
                        <div class="main-card mb-3 custom_card">
                            <div>
                                <div class="tab-content">
                                    <div class="card-header">
                                        <h5 class="card-title" style="margin-top: 10px">Acoustics </h5>
                                    </div>
                                    <div>
                                        <div class="form-row">

                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="accoustics">Acoustics
                                                        @if (!empty($tooltip->accoustics))
                                                            <script type="text/javascript">
                                                                document.write(Tooltip('{{ $tooltip->accoustics }}'));
                                                            </script>
                                                        @endif
                                                    </label>
                                                    <select name="accoustics" id="accoustics" class="form-control">
                                                        <option value="">Select Acoustics</option>
                                                        <option value="Yes" @if (isset($Item['Accoustics']))
                                                            @if ($Item['Accoustics'] == 'Yes')
                                                                {{ 'selected' }}
                                                            @endif
                                                            @endif>Yes
                                                        </option>
                                                        <option value="No" @if (isset($Item['Accoustics']))
                                                            @if ($Item['Accoustics'] == 'No')
                                                                {{ 'selected' }}
                                                            @endif
                                                        @else
                                                            {{ 'selected' }}
                                                            @endif>No
                                                        </option>

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="rWdBRating">rW dB Rating
                                                        @if (!empty($tooltip->rWdBRating))
                                                            <script type="text/javascript">
                                                                document.write(Tooltip('{{ $tooltip->rWdBRating }}'));
                                                            </script>
                                                        @endif
                                                    </label>
                                                    <input name="rWdBRating" id="rWdBRating" class="form-control" readonly type="text" value="@if (isset($Item['rWdBRating'])){{ $Item['rWdBRating'] }}@endif">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="position-relative form-group input-icons">
                                                    <label for="perimeterSeal1">Perimeter Seal 1
                                                        @if (!empty($tooltip->perimeterSeal1))
                                                            <script type="text/javascript">
                                                                document.write(Tooltip('{{ $tooltip->perimeterSeal1 }}'));
                                                            </script>
                                                        @endif
                                                    </label>

                                                    <i class="fa fa-info icon cursor-pointer"
                                                        id="perimeterSeal1Icon"></i>
                                                    <input type="text" readonly id="perimeterSeal1"
                                                        class="form-control" value="@if (isset($Item['perimeterSeal1'])) {{ $Item['perimeterSeal1'] }} @endif">
                                                    <input type="hidden" name="perimeterSeal1"
                                                        value="@if (isset($Item['perimeterSeal1'])) {{ $Item['perimeterSeal1'] }} @endif">

                                                    <!-- <select name="perimeterSeal1" id="perimeterSeal1" class="form-control"
                                                    @if (isset($Item['Accoustics']))
                                                        @if ($Item['Accoustics'] != 'Yes')
                                                            {{ 'disabled' }}
                                                @else
                                                            {{ 'required' }}
                                                        @endif
                                            @else
                                                        {{ 'disabled' }}
                                                    @endif
                                                    >
                                                        <option value="">Select Perimeter Seal 1</option>
                                                        @foreach ($option_data as $row)
                                                            @if ($row->UnderAttribute == 'Perimeter_Seal_1')
                                                                <option value="{{ $row->OptionKey }}"
                                                                    @if (isset($Item['perimeterSeal1']))
                                                                        @if ($Item['perimeterSeal1'] == $row->OptionKey)
                                                                            {{ 'selected' }}
                                                                        @endif
                                                                    @endif
                                                                >{{ $row->OptionValue }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select> -->
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group input-icons">
                                                    <label for="perimeterSeal2">Perimeter Seal 2
                                                        @if (!empty($tooltip->perimeterSeal2))
                                                            <script type="text/javascript">
                                                                document.write(Tooltip('{{ $tooltip->perimeterSeal2 }}'));
                                                            </script>
                                                        @endif
                                                    </label>
                                                    <i class="fa fa-info icon cursor-pointer"
                                                        id="perimeterSeal2Icon"></i>
                                                    <input type="text" readonly id="perimeterSeal2"
                                                        class="form-control" value="@if (isset($Item['perimeterSeal2'])) {{ $Item['perimeterSeal2'] }} @endif">
                                                    <input type="hidden" name="perimeterSeal2"
                                                        value="@if (isset($Item['perimeterSeal2'])) {{ $Item['perimeterSeal2'] }} @endif">

                                                </div>
                                            </div>

                                        {{--    <div class="col-md-6">
                                                <div class="position-relative form-group input-icons">
                                                    <label for="thresholdSeal1">Threshold Seal 1
                                                        @if (!empty($tooltip->thresholdSeal1))
                                                            <script type="text/javascript">
                                                                document.write(Tooltip('{{ $tooltip->thresholdSeal1 }}'));
                                                            </script>
                                                        @endif
                                                    </label>
                                                    <i class="fa fa-info icon cursor-pointer "
                                                        id="thresholdSeal1Icon"></i>
                                                    <input type="text" readonly id="thresholdSeal1"
                                                        class="form-control" value="@if (isset($Item['thresholdSeal1'])) {{ $Item['thresholdSeal1'] }} @endif">
                                                    <input type="hidden" name="thresholdSeal1" value="@if (isset($Item['thresholdSeal1'])) {{ $Item['thresholdSeal1'] }} @endif">



                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group input-icons">
                                                    <label for="thresholdSeal2">Threshold Seal 2
                                                        @if (!empty($tooltip->thresholdSeal2))
                                                            <script type="text/javascript">
                                                                document.write(Tooltip('{{ $tooltip->thresholdSeal2 }}'));
                                                            </script>
                                                        @endif
                                                    </label>
                                                    <i class="fa fa-info icon cursor-pointer"
                                                        id="thresholdSeal2Icon"></i>
                                                    <input type="text" readonly id="thresholdSeal2"
                                                        class="form-control" value="@if (isset($Item['thresholdSeal2'])) {{ $Item['thresholdSeal2'] }} @endif">
                                                    <input type="hidden" name="thresholdSeal2" value="@if (isset($Item['thresholdSeal2'])) {{ $Item['thresholdSeal2'] }} @endif">


                                                </div>
                                            </div>  --}}
                                            <div class="col-md-6">
                                                <div class="position-relative form-group input-icons">
                                                    <label for="accousticsmeetingStiles">Meeting Stiles
                                                        @if (!empty($tooltip->accousticsmeetingStiles))
                                                            <script type="text/javascript">
                                                                document.write(Tooltip('{{ $tooltip->accousticsmeetingStiles }}'));
                                                            </script>
                                                        @endif
                                                    </label>
                                                    <i class="fa fa-info icon cursor-pointer"
                                                        id="accousticsmeetingStilesIcon"></i>
                                                    <input type="text" readonly id="accousticsmeetingStiles"
                                                        class="form-control"  value="@if (isset($Item['AccousticsMeetingStiles'])) {{ $Item['AccousticsMeetingStiles'] }} @endif">
                                                    <input type="hidden" name="accousticsmeetingStiles" value="@if (isset($Item['AccousticsMeetingStiles'])) {{ $Item['AccousticsMeetingStiles'] }} @endif">



                                                </div>
                                            </div>



                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
