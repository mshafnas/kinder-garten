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
                <h6 class="m-0 font-weight-bold text-primary">Events</h6>
                <a href="#" class="btn btn-primary btn-icon-split" style="float:right !important; margin-top: -23px;" data-toggle="modal" data-target="#exampleModal">
                    <span class="icon text-white-50">
                        <i class="fas fa-plus"></i>
                    </span>
                    <span class="text">Add Event</span>
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Venue</th>
                                <th>Created By</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Title</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Venue</th>
                                <th>Created By</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <form id="eventForm" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Event</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" name="title" id="title" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date">Date</label>
                                <input type="date" name="date" id="date" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="time">Time</label>
                                <input type="time" name="time" id="time" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="venue">Venue</label>
                                <input type="text" name="venue" id="venue" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="pics">Images</label>
                                <input type="file" name="pics[]" id="pics" class="form-control" multiple required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <span id="btn-holder"><input type="submit" class="btn btn-primary" value="Submit"></span>
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
            var table = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.getEvent') }}",
                columns: [
                    {data: 'title', name: 'Title'},
                    {data: 'date', name: 'Date'},
                    {data: 'time', name: 'Time'},
                    {data: 'venue', name: 'Venue'},
                    {data: 'user_id', name: 'Created By'},
                    {data: 'created_at', name: 'Created At'},
                    {
                        data: 'action', 
                        name: 'action', 
                        orderable: true, 
                        searchable: true
                    },
                ]
            });

            
            // submit form
            $('#eventForm').submit((event) => {
                event.preventDefault();
                var formData = new FormData(document.getElementById("eventForm"));
                $.ajax({
                    url: '/admin/event/create',
                    method: 'POST',
                    data: formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: (response) => {
                        alert(response);
                    },
                    error: (response) => {
                        alert('das');
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

    </script>
@endsection