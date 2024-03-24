@extends('layouts/app')

@section('css')
    <link href="https://cdn.datatables.net/2.0.2/css/dataTables.dataTables.css" rel="stylesheet">
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script>

    <script>
        new DataTable('#user-table', {
            ordering: false,
            processing: true,
            serverSide: true,
            lengthChange: false,
            "ajax": "/user/datatable",
        });

        new DataTable('#daily-record-table', {
            lengthChange: false,
            bInfo: false,
            ordering: false,
            searching: false,
            ajax: '/daily-record/datatable'
        });

        function onDelete(id, name) {
            const r = confirm("Are you sure want to delete " + name + "?")
            if (r === true) {
                $.post('/user/' + id, {
                    _method: 'DELETE',
                    _token: '{{ csrf_token() }}'
                }).done(function(){
                    console.log('deleted')
                    window.location.reload()
                })
            }
        }
    </script>
@endsection

@section('content')
<div class="container">
    <div class="justify-content-center">
        <div class="card">

            <div class="card-body">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">User</a>
                        <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Daily Record</a>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                        <table id="user-table" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Age</th>
                                    <th>Gender</th>
                                    <th>Created At</th>
                                    <th>  </th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                        <table id="daily-record-table" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Male Count</th>
                                    <th> Female Count </th>
                                    <th>Male Average Age</th>
                                    <th>Female Average Age</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
                
                
            </div>
        </div>
    </div>
</div>
@endsection
