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
                <h6 class="m-0 font-weight-bold text-primary">Monthly Fee</h6>
                <a href="#" class="btn btn-primary btn-icon-split" style="float:right !important; margin-top: -23px;" data-toggle="modal" data-target="#feeAddModal">
                    <span class="icon text-white-50">
                        <i class="fas fa-plus"></i>
                    </span>
                    <span class="text">Add Fee</span>
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="feeTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Index Number</th>
                                <th>Group</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Month</th>
                                <th>Year</th>
                                <th>Paid On</th>
                                <th>Group ID</th>
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
    <div class="modal fade" id="feeAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <form id="feeForm" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Monthly Fee</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="student_id">Student</label>
                                <select name="student_id" id="student_id" class="form-control" required>
                                    {!!$students!!}
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="month_year">Month & Year</label>
                                <input type="month" name="month_year" id="monnth_year" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="amount">Amount</label>
                                <input type="number" name="amount" id="amount" class="form-control contact" required>
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

    <div class="modal fade" id="feeUpdateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="feeUpdateForm" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Update Monthly Fee</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="feeUpdateModalBody"></div>
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
    <script src="{{ asset('dashboard/js/moment.min.js') }}"></script>
    <script>

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var table = $('#feeTable').DataTable({
                dom: '<"toolbar">frtip',
                ajax: "{{ route('admin.getFees') }}",
                searching: true,
                filter: true,
                columns: [
                    {data: 'index_no', name: 'Index No'},
                    {data: 'group_title', name: 'Group'},
                    {data: 'first_name', name: 'First Name'},
                    {data: 'last_name', name: 'Last Name'},
                    {data: 'fee_month', name: 'Month', render: function(data, type) {
                        return moment.months(data - 1);
                    } },
                    {data: 'fee_year', name: 'Year'},
                    // {data: 'contact_no', name: 'Contact No'},
                    {data: 'created_at', name: 'Paid On', render: function(data, type) {
                        return moment(data).format("YYYY-MM-DD");
                    } },
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
            
            

            // $('div.toolbar').html('<div class="row"><div class="col-md-2"><div class="form-group"><label for="group-list">Group</label><select id="group_filter" name="group_filter" class="form-control">'+groupHtml+'</select></div></div></div>');
            // reset form
            $('#reset-org').click(() => {
                $('#feeForm').trigger("reset");
            });

            // filter 
            // $.fn.dataTable.ext.search.push(
            //     function (settings, data, dataIndex) {
            //     var selectedItem = $('#group_filter').val()
            //     var group = data[8];
            //     if (selectedItem === "" || group.includes(selectedItem)) {
            //         return true;
            //     }
            //     return false;
            //     }
            // );
            // $('#group_filter').change(() => {
            //     table.draw();
            // });

            
            
            
            // submit form
            $('#feeForm').submit((event) => {
                event.preventDefault();
                var formData = new FormData(document.getElementById("feeForm"));
                $.ajax({
                    url: '/admin/fee/create',
                    method: 'POST',
                    data: formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: (response) => {
                        toastr.success('Fee is added successfully');
                        $('#feeForm').trigger("reset");
                        $('#feeAddModal').modal('hide');
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

            $('#feeUpdateForm').submit((event) => {
                event.preventDefault();
                var formData = new FormData(document.getElementById("feeUpdateForm"));
                $.ajax({
                    url: '/admin/fee/update',
                    method: 'POST',
                    data: formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: (response) => {
                        toastr.success('Fee is updated successfully');
                        $('#feeUpdateForm').trigger("reset");
                        $('#feeUpdateModal').modal('hide');
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

        const deleteFee = (id) => {
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
                    url: '/admin/fee/delete',
                    method: 'POST',
                    data: {
                        id: id,
                    },
                    success: (response) => {
                        if (response.error) {
                            toastr.error(response.message);
                        }
                        toastr.success('Record has been deleted.');
                        $('#feeTable').DataTable().ajax.reload();
                    },
                    error: (response) => {
                        toastr.error('Internal server error.');
                    }
                });
            }
            })
        }

        const updateFee = (id) => {
            $.ajax({
                url: '/admin/fee/get-fee',
                method: 'POST',
                data: {
                    id: id,
                },
                success: (response) => {
                    $('#feeUpdateModalBody').html(response);
                    $('#feeUpdateModal').modal('show');
                },
                error: (response) => {
                    toastr.error('Internal server error.');
                }
            });
        }

    </script>
@endsection