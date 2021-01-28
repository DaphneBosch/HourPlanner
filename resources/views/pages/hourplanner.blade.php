@extends('layouts/layout')

@section('title', 'HourPlanner | My HourPlanner')

@section('content')

    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2 class="title-hour">My Hourplanner</h2>
                </div>
                <div class="col" style="text-align: center; margin-top: 50px;">
                    <a class="btn btn-success button-text" onClick="add()" href="javascript:void(0)"><i class="fas fa-plus"></i> Add hours</a>
                </div>
            </div>
        </div>
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif

        <div class="container mt-5">
            <table class="table table-bordered" id="hours">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Hour one</th>
                    <th>Hour two</th>
                    <th>Total hours</th>
                    <th>Created at</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>

        <div class="modal fade" id="hours-modal" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="HoursModal"></h4>
                    </div>
                    <div class="modal-body">

                        <form action="javascript:void(0)" id="HoursForm" name="HoursForm"
                              class="form-horizontal"
                              method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" id="id">
                            <div class="form-group">
                                <label for="hour_one">Hour one</label>
                                <input type="text" class="form-control" id="hour_one"
                                       name="hour_one"
                                       placeholder="Hour one">
                            </div>
                            <div class="form-group">
                                <label for="hour_two">Hour two</label>
                                <input type="text" class="form-control"
                                       id="hour_two" name="hour_two" placeholder="Hour two">
                            </div>
                            <div class="form-group">
                                <label for="total_hours">Total hours</label>
                                <input type="text" class="form-control"
                                       id="total_hours" name="total_hours" placeholder="Total hours">
                            </div>
                            <div class="col-sm-offset-2">
                                <button type="submit" class="btn btn-success" id="btn-save">Save
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deletehours" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" id="emptitle">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure to remove this row?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="btn-delete" onclick="confirmDelete()">Remove</button>
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <!--Table-->

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script type="text/javascript">

        $(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#hours').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: "{{ route('hourplanner') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'hour_one', name: 'hour_one'},
                    {data: 'hour_two', name: 'hour_two'},
                    {data: 'total_hours', name: 'total_hours'},
                    {data: 'created_at', name: 'created_at'},
                    {
                        data: 'action',
                        name: 'action',
                        orderable: true,
                        searchable: true
                    },
                ],
                order: [[0, 'desc']]
            });
        });

        function add() {
            $('#HoursForm').trigger("reset");
            $('#HoursModal').html("Add hours");
            $('#hours-modal').modal('show');
            $('#id').val('');
        }

        function editFunc(id) {
            $.ajax({
                type: "POST",
                url: "{{ url('edit-hour') }}",
                data: {id: id},
                dataType: 'json',
                success: function (res) {
                    $('#HoursModal').html("Change hours");
                    $('#hours-modal').modal('show');
                    $('#id').val(res.id);
                    $('#hour_one').val(res.hour_one);
                    $('#hour_two').val(res.hour_two);
                    $('#total_hours').val(res.total_hours);
                    $("#hours-modal").modal('hide');
                },

                error: function (data) {
                    console.log(data);
                    toastr.error('Werknemer niet gewijzigd', 'Foutmelding')
                }
            });
        }

        let employeeid = "";

        function deleteFunc(id) {

            employeeid = id;

            $("#deletehours").modal('show');
            $('#emptitle').html("Delete hours");
        }

        function confirmDelete() {
            $.ajax({
                type: "POST",
                url: "{{ url('delete-hour') }}",
                data: {id: employeeid},
                dataType: 'json',
                success: function (res) {
                    employeeid = "";
                    var oTable = $('#hours').dataTable();
                    oTable.fnDraw(false);
                    $("#btn-delete").html('Verwijder');
                    $("#deletehours").modal('hide');
                    toastr.success('Werknemer verwijderd!', 'Success')
                },
                error: function (data) {
                    console.log(data);
                    toastr.error('Werknemer niet verwijderd', 'Foutmelding')
                }
            });
        }

        $('#HoursForm').submit(function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: "{{ url('store-hour')}}",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: (data) => {
                    $("#hours-modal").modal('hide');
                    var oTable = $('#hours').dataTable();
                    oTable.fnDraw(false);
                    $("#btn-save").html('Submit');
                    $("#btn-save").attr("disabled", false);
                    toastr.success('Opgeslagen!', 'Success')
                },
                error: function (data) {
                    console.log(data);
                    toastr.error('Niet opgeslagen', 'Foutmelding')
                }
            });
        });

    </script>

@endsection
