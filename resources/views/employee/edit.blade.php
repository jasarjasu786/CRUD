@extends('theme.layout')
   
@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Edit Employee</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-info" href="{{ route('employee.index') }}"> Back</a>
        </div>
    </div>
</div>

@if ($errors->any())
        <div class="alert alert-danger">
            <strong>Warning!</strong> Please check your fields.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
@endif

<form action="{{ route('employee.update',$employee->empl_id) }}" method="POST">
@csrf
@method('PUT')
  <div class="form-group">
    <label for="topic">Topic title</label>
    <input type="text" class="form-control" value="{{ $employee->name }}" placeholder="Enter Topic" name ="topic">
  </div>
  <div class="form-group">
    <label for="description">Description</label>
    <textarea class="form-control" rows="3" name="description">{{ $employee->name }}</textarea>
  </div>
<div class="form-group">
    <label for="categorie">Categorie</label>
    <input type="text" class="form-control" value="{{ $employee->image }}" placeholder="Enter Categorie" name ="categorie">
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>

@endsection