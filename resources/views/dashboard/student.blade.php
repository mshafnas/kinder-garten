@extends('layouts.dashboard_app')
@section('custom-styles')
    <link href="{{ asset('dashboard/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div>
        {{-- <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Events</h1>
        </div> --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3" style="float: right">
                <h6 class="m-0 font-weight-bold text-primary">Students</h6>
                <a href="#" class="btn btn-primary btn-icon-split" style="float:right !important; margin-top: -23px;" data-toggle="modal" data-target="#studentAddModal">
                    <span class="icon text-white-50">
                        <i class="fas fa-plus"></i>
                    </span>
                    <span class="text">Add Student</span>
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="studentTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Index Number</th>
                                <th>Group</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Date of Birth</th>
                                <th>Age</th>
                                <th>Contact No</th>
                                <th>Whatsapp No</th>
                                <th>Address</th>
                                <th>Gr</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        {{-- <tfoot>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Created At</th>
                            </tr>
                        </tfoot> --}}
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="studentAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <form id="studentForm" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Student</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Group</label>
                                <select name="group_id" id="group_id" class="form-control" required>
                                    {!!$groups!!}
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="index_no">Index Number</label>
                                <input type="text" name="index_no" id="index_no" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <input type="text" name="first_name" id="first_name" class="form-control" required>
                            </div>
                        </div><div class="col-md-6">
                            <div class="form-group">
                                <label for="last_name">Last Name</label>
                                <input type="text" name="last_name" id="last_name" class="form-control" required>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="dob">Date of Birth</label>
                                <input type="date" name="dob" id="dob" class="form-control" required>
                            </div>
                        </div><div class="col-md-6">
                            <div class="form-group">
                                <label for="age">Age</label>
                                <input type="text" name="age" id="age" class="form-control" required>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="contact_no">Contact No</label>
                                <input type="number" name="contact_no" id="contact_no" class="form-control contact" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="whatsapp_no">Whatsapp No</label>
                                <input type="number" name="whatsapp_no" id="whatsapp_no" class="form-control contact" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="address">Address</label>
                                <input type="text" name="address" id="address" class="form-control" required>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="reset-org">Close</button>
                    <span id="btn-holder"><input type="submit" class="btn btn-primary" value="Submit"></span>
                </div>
            </form>
        </div>
        </div>
    </div>

    <div class="modal fade" id="studentUpdateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="studentUpdateForm" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Update Student</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="studentUpdateModalBody"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <span id="btn-holder-update"><input type="submit" class="btn btn-primary" value="Update"></span>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('custom-scripts')
    <script src="{{ asset('dashboard/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('dashboard/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script>

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var table = $('#studentTable').DataTable({
                dom: '<"toolbar">frtip',
                ajax: "{{ route('admin.getStudents') }}",
                searching: true,
                columns: [
                    {data: 'index_no', name: 'Index No'},
                    {data: 'group_title', name: 'Group'},
                    {data: 'first_name', name: 'First Name'},
                    {data: 'last_name', name: 'Last Name'},
                    {data: 'dob', name: 'DOB'},
                    {data: 'age', name: 'Age'},
                    {data: 'contact_no', name: 'Contact No'},
                    {data: 'whatsapp_no', name: 'Whatsapp No'},
                    {data: 'address', name: 'Address'},
                    {data: 'group_id', name: 'Group ID', visible: false},
                    // {data: 'created_at', name: 'Created At'},
                    {
                        data: 'action', 
                        name: 'action', 
                        orderable: true, 
                        searchable: true
                    },
                ],
            });
            
            const groupHtml = {!! json_encode($groups, JSON_HEX_TAG) !!}

            $('div.toolbar').html('<div class="row"><div class="col-md-2"><div class="form-group"><label for="group-list">Group</label><select id="group_filter" name="group_filter" class="form-control">'+groupHtml+'</select></div></div></div>');
            // reset form
            $('#reset-org').click(() => {
                $('#studentForm').trigger("reset");
            });

            // filter 
            $.fn.dataTable.ext.search.push(
                function (settings, data, dataIndex) {
                var selectedItem = $('#group_filter').val()
                var group = data[8];
                if (selectedItem === "" || group.includes(selectedItem)) {
                    return true;
                }
                return false;
                }
            );
            $('#group_filter').change(() => {
                table.draw();
            });

            
            const validatePhoneNumber = (mode) => {
                let phone = $('#contact_no').val();
                let editPhone = $('#edit_contact_no').val();
                if (mode == 'add' && phone.length != 10) {
                    toastr.error('Please enter proper contact number.');
                    return false;
                } else if (mode == 'edit' && editPhone.length != 10) {
                    toastr.error('Please enter proper whatsapp number.');
                    return false;
                } else {
                    return true;
                }
            }
            
            // submit form
            $('#studentForm').submit((event) => {
                event.preventDefault();
                var formData = new FormData(document.getElementById("studentForm"));
                const isValidPhone = validatePhoneNumber('add');
                if (isValidPhone === false) {
                    return false;
                }
                $.ajax({
                    url: '/admin/student/create',
                    method: 'POST',
                    data: formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: (response) => {
                        toastr.success('Student is added successfully');
                        $('#studentForm').trigger("reset");
                        $('#studentAddModal').modal('hide');
                        table.ajax.reload();
                    },
                    error: (response) => {
                        toastr.error('Oops something went wrong');
                    },
                    ajaxStart: function(){
                        const loaderBtnHtml = '<button class="btn btn-primary"><i class="fas fa-spinner spin"></i></button>';
                        $('#btn-holder').html(loaderBtnHtml);
                    },
                    ajaxStop: function(){ 
                        const submitBtnHtml = '<input type="submit" class="btn btn-primary" value="Submit">'; 
                        $('#btn-holder').html(submitBtnHtml);
                    }
                });
            });

            $('#studentUpdateForm').submit((event) => {
                event.preventDefault();
                var formData = new FormData(document.getElementById("studentUpdateForm"));
                const isValidPhone = validatePhoneNumber('edit');
                if (isValidPhone === false) {
                    return false;
                }
                $.ajax({
                    url: '/admin/student/update',
                    method: 'POST',
                    data: formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: (response) => {
                        toastr.success('Student is updated successfully');
                        $('#studentUpdateForm').trigger("reset");
                        $('#studentUpdateModal').modal('hide');
                        table.ajax.reload();
                    },
                    error: (response) => {
                        toastr.error('Oops something went wrong');
                    },
                    ajaxStart: function(){
                        const loaderBtnHtml = '<button class="btn btn-primary"><i class="fas fa-spinner spin"></i></button>';
                        $('#btn-holder').html(loaderBtnHtml);
                    },
                    ajaxStop: function(){ 
                        const submitBtnHtml = '<input type="submit" class="btn btn-primary" value="Submit">'; 
                        $('#btn-holder').html(submitBtnHtml);
                    }
                });
            });

        });

        const deleteStudent = (id) => {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/admin/student/delete',
                    method: 'POST',
                    data: {
                        id: id,
                    },
                    success: (response) => {
                        if (response.error) {
                            toastr.error(response.message);
                        }
                        toastr.success('Record has been deleted.');
                        $('#studentTable').DataTable().ajax.reload();
                    },
                    error: (response) => {
                        toastr.error('Internal server error.');
                    }
                });
            }
            })
        }

        const updateStudent = (id) => {
            $.ajax({
                url: '/admin/student/get-student',
                method: 'POST',
                data: {
                    id: id,
                },
                success: (response) => {
                    $('#studentUpdateModalBody').html(response);
                    $('#studentUpdateModal').modal('show');
                },
                error: (response) => {
                    toastr.error('Internal server error.');
                }
            });
        }

    </script>
@endsection