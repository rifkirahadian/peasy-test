@extends('layouts/app')

@section('css')
    <link href="https://cdn.datatables.net/2.0.2/css/dataTables.dataTables.css" rel="stylesheet">
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script>

    <script>
        new DataTable('#example', {
            bInfo: false,
            processing: true,
            serverSide: true,
            lengthChange: false,
            "ajax": "/user/datatable",
        });
    </script>
@endsection

@section('content')
<div class="container">
    <div class="justify-content-center">
        <div class="card">
            <div class="card-header">User</div>

            <div class="card-body">
                <table id="example" class="display" style="width:100%">
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
        </div>
    </div>
</div>
@endsection
