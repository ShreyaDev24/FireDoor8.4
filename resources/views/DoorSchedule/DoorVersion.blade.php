<table class="table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <!-- <th><input type="checkbox" class="check" id="selectAll" > Select All</th> -->
                                                <th>Line</th>
                                                <th>Label</th>
                                                <th>Item</th>
                                                <th>Qty</th>
                                                <th>Price</th>
                                                <th>Total</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                @if(!empty($data) && count($data)>0)
                                <?php  $index =0; $SI=1;?>
                                    @foreach($data as $row)
                                            <tr>
                                                <!-- <td><input type="checkbox" class="check" value="{{$row->itemId}}"></td> -->
                                                <td>{{$index}} <input type="hidden" class="check" value="{{$row->itemId}}"><input type="hidden" class="doors_{{$index}}" value="{{$row->id}}"></td>
                                                <td>{{$row->DoorNumber}}</td>
                                                <td>@if($row->DoorsetType=='DD'){{'Double Door'}}@elseif($row->DoorsetType=='SD'){{'Single Door'}} @else {{"Leaf and a half"}} @endif</td>
                                                <td><input type="number"  style="width: 100%;" readonly id="quantity" value="1" name="quantity" min="1" max="100" class="quantity_{{$index}}"></td>
                                                <td>{{$row->DoorsetPrice}}</td>
                                                <td>{{$row->DoorsetPrice}}</td>
                                                <td class="text-center">
                                                <div class="dropdown">
                                                    <a class="dropdown-toggle btn btn-light" type="button" data-toggle="dropdown"><i class="fa fa-ellipsis-h"></i></a>
                                                    <ul class="dropdown-menu">
                                                        <li><a href="#">Change Option</a></li>
                                                        <li><a href="#">Name</a></li>
                                                        <li><a href="#">Configuration</a></li>
                                                        <li><a href="#">Adjust Price</a></li>
                                                        <li><a href="#">Comment</a></li>
                                                        <li><a href="#">Copy</a></li>
                                                        <li><a href="#">Export</a></li>
                                                        <li><a href="#">Remove</a></li>
                                                    </ul>
                                                </div>
                                                </td>
                                            </tr>
                                            <?php $index++; $SI++; ?>
                                        @endforeach
                                    @endif
                                           

                                            </tbody>
                                        </table>