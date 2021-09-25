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
                                <a class="btn btn-info" href="{{ route('employee.index') }}"> Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-4">Create an Employee!</h1>
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Warning!</strong> Please check your fields<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                    <form class="user" action="{{ route('employee.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group ">
                                <input type="text" class="form-control"  placeholder="Name" name="name" id="name">   
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control" name="email" id="email" placeholder="Email Address">
                        </div>
                        <div class="form-group ">
                            <select class="form-control" name="desig_id" id="desig_id">
                                @foreach ($designations as $item)
                                    <option value="{{$item->id}}">{{$item->designation}}</option>
                                @endforeach
                                
                            </select> 
                        </div>
                        <div class="form-group">
                            <input type="file" class="form-control " id="image">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>


                </div>
            </div>
        </div>
    </div>
</div>

@endsection