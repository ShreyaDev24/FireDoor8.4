@extends("layouts.Master")

@section("main_section")

<div class="app-main__outer">
    <div class="app-main__inner">
        @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-check"></i> Alert!</h5>
            {{ session()->get('success') }}
        </div>
        @endif
        <div class="main-card mb-3 card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="card-header"><h5 class="card-title">Company List</h5></div>
                    </div>
                    <div class="col-sm-6 ">
                        <a href="{{route('company/add')}}" class="btn-shadow btn btn-info float-right">
                            Add New
                        </a>
                    </div>
                </div>
                <hr>

                <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered">
                    <thead class="text-uppercase table-header-bg">
                        <tr class="text-white">
                            <th>Company Name</th>
                            <th>Contact</th>
                            <th>Phone</th>
                            <th>E-Mail</th>
                            <th>Address</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $row)
                        <tr>
                            <td> <a href="{{url('company/details/'.$row->id)}}">{{{$row->CompanyName}}}</a></td>
                            <td>{{$row->FirstName}} {{$row->LastName}}</td>
                            <td>{{$row->CompanyPhone}}</td>
                            <td>{{$row->UserEmail}}</td>
                            <td>{{$row->CompanyAddressLine1}} </td>
                            <td style="width: 100px">
                                <a href="{{url('company/edit/'.$row->id)}}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                                {{-- ✅ NEW BUTTON --}}
                                @if(auth()->user()->UserType == 1)
                                <a href="javascript:void(0);"
                                class="btn btn-primary"
                                onclick="openSuperAdminModal({{$row->UserId}})">
                                    <i class="fa fa-lock"></i>
                                </a>
                                @endif
                                {{-- ✅ ENDNEW BUTTON --}}
                                <a href="javascript:void(0);" onClick="deleteCompany({{$row->UserId}});" class="btn btn-danger"><i class="fa fa-trash"></i></a>

                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>
    <button style="display: none;" type="button" id="success-alert" data-type="success"
        class="btn btn-success btn-show-swal"></button>
    <form action="{{route('deleteCompany')}}" method="post" id="deleteCompany">
        {{ csrf_field() }}
        <input type="hidden" name="companyId" id="companyId">
    </form>
    @endsection

    @section('js')
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            function openSuperAdminModal(userId) {

                $('#edit_user_id').val(userId);

                $('#user_info_box').html('');
                $('#company_error').html('');
                $('#module_error').html('');

                $('.company-checkbox').prop('checked', false);
                $('.module-checkbox').prop('checked', false);

                $.ajax({
                    url: '/admins/edit-superadmin/' + userId,
                    type: 'GET',
                    success: function(res) {

                        if (res.status) {

                            $('#user_info_box').html(
                                '<strong>' + res.user.FirstName + ' ' + res.user.LastName + '</strong><br>' +
                                '<small>' + res.user.UserEmail + '</small>'
                            );

                            $.each(res.assigned_company_ids, function(i, id) {
                                $('#company_' + id).prop('checked', true);
                            });

                            $.each(res.assigned_modules, function(i, moduleName) {
                                $('#module_' + moduleName).prop('checked', true);
                            });

                            $('#superAdminModal').modal('show');
                        }
                    },
                    error: function() {
                        alert('Data load nahi hua');
                    }
                });
            }
            $('#superAdminForm').submit(function(e){
                e.preventDefault();

                var userId = $('#edit_user_id').val();
                var companies = [];
                var modules = [];

                $('#company_error').html('');
                $('#module_error').html('');

                $('.company-checkbox:checked').each(function(){
                    companies.push($(this).val());
                });

                $('.module-checkbox:checked').each(function(){
                    modules.push($(this).val());
                });

                if (companies.length == 0) {
                    $('#company_error').html('Please select at least one company');
                    return;
                }

                if (modules.length == 0) {
                    $('#module_error').html('Please select at least one module');
                    return;
                }

                $.ajax({
                    url: '/admins/update-superadmin-access/' + userId,
                    type: 'POST',
                    data: {
                        _token: $('input[name="_token"]').val(),
                        company_ids: companies,
                        modules: modules
                    },
                    success: function(res){
                        if(res.status){
                            alert(res.message);
                            $('#superAdminModal').modal('hide');
                            location.reload();
                        } else {
                            alert('Something went wrong');
                        }
                    },
                    error: function(){
                        alert('Save failed');
                    }
                });
            });
            function deleteCompany(companyId){
                var r = confirm("Are you sure! you wan't to delete company. If you deleted company it delete all other data which is related to company and it's not revert process.");
                if (r == true) {
                    $('#companyId').val(companyId);
                    $('#deleteCompany').submit();
                }
            }
        </script>





    @if(session()->has('updated'))
    <script type="text/javascript">
    swal(
        'Success',
        'Company updated Succesfully!',
        'success'
    )


    </script>
    @endif

    @if(session()->has('added'))
    <script type="text/javascript">
    swal(
        'Success',
        'Company added Succesfully!',
        'success'
    )
    </script>
    @endif
    @endsection

    {{-- Modal --}}
<div class="modal fade" id="superAdminModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="superAdminForm">
            @csrf
            <input type="hidden" id="edit_user_id" name="user_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Super Admin Access</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label"><strong>User</strong></label>
                        <div id="user_info_box" class="border rounded p-3 bg-light"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            <strong>Do you want to make this Super Admin?</strong>
                        </label>
                        <select class="form-control" id="make_super_admin" name="make_super_admin">
                            <option value="yes">Yes</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Select Company Access</strong></label>
                        <div class="row" id="company_checkbox_list">
                            @foreach($data as $company)
                                <div class="col-md-6 mb-2">
                                    <div class="form-check border rounded p-2">
                                        <input
                                            class="form-check-input company-checkbox"
                                            type="checkbox"
                                            value="{{ $company->id }}"
                                            id="company_{{ $company->id }}"
                                            name="company_ids[]"
                                        >
                                        <label class="form-check-label ms-2" for="company_{{ $company->id }}">
                                            {{$company->FirstName}} {{$company->LastName}}  ({{$company->UserEmail}})
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-danger mt-2" id="company_error"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Select Module Access</strong></label>

                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <div class="form-check border rounded p-2">
                                    <input class="form-check-input module-checkbox" type="checkbox" value="dashboard" id="module_dashboard" name="modules[]">
                                    <label class="form-check-label ms-2" for="module_dashboard">Dashboard</label>
                                </div>
                            </div>

                            <div class="col-md-4 mb-2">
                                <div class="form-check border rounded p-2">
                                    <input class="form-check-input module-checkbox" type="checkbox" value="setting" id="module_setting" name="modules[]">
                                    <label class="form-check-label ms-2" for="module_setting">Setting</label>
                                </div>
                            </div>

                            <div class="col-md-4 mb-2">
                                <div class="form-check border rounded p-2">
                                    <input class="form-check-input module-checkbox" type="checkbox" value="selected_option" id="module_selected_option" name="modules[]">
                                    <label class="form-check-label ms-2" for="module_selected_option">Selected Option</label>
                                </div>
                            </div>
                        </div>

                        <div class="text-danger mt-2" id="module_error"></div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save Changes</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>
