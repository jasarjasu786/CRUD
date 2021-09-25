@extends('theme.layout')



@section('content')
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

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('employee.create') }}"> Create new Employee</a>
        </div>
    </div>
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
    {{-- <tbody>
        @foreach ($employees as $employee)
        <tr>
            <th scope="row">{{ $employee->empl_id }}</th>
            <td>{{ $employee->image }}</td>
            <td>{{ $employee->name }}</td>
            <td>{{ $employee->email }}</td>
            <td>{{ $employee->designation }}</td>
            <td>
                <form action="{{ route('employee.destroy',$employee->empl_id) }}" method="POST">

                    <a class="btn btn-info" href="{{ route('employee.show',$employee->empl_id) }}">Show</a>

                    <a class="btn btn-primary" href="{{ route('employee.edit',$employee->empl_id) }}">Edit</a>

                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
        {{ $employees->links() }}
    </tbody> --}}
</table>
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

