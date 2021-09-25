@extends('theme.layout')



@section('content')


<div class="card o-hidden border-0 shadow-lg my-5">
    <div class="card-body p-0">
        <!-- Nested Row within Card Body -->
        <div class="row">

            <div class="col-lg-10">
                <div class="p-5">
                    <div class="row">
                        <div class="col-lg-12 margin-tb">
                            <div class="pull-right">
                                <a class="btn btn-primary" href="{{ route('employee.create') }}"> Create new Employee</a>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-4">Employee View!</h1>
                        @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                        @endif
                        @if ($message = Session::get('error'))
                        <div class="alert alert-danger">
                            <p>{{ $message }}</p>
                        </div>
                        @endif
                    </div>

                    <table class="table table-striped table-bordered  data-table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col"></th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Designation</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>




@endsection
@section('custom-script')
{{-- <script src="//code.jquery.com/jquery.js"></script> --}}
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
       
      
      var table = $('.data-table').DataTable({
          processing: true,
          serverSide: true,
          ajax: '/employeeData',
          columns: [
              {data: 'empl_id', name: 'empl_id'},
              {data: 'image', name: 'image'},
              {data: 'name', name: 'name'},
              {data: 'email', name: 'email'},
              {data: 'designation', name: 'designation'},
              {data: 'action', name: 'action', orderable: false, searchable: false},
          ]
      });
      
    });
</script>
@endsection