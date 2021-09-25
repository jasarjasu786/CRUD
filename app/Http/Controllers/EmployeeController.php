<?php

namespace App\Http\Controllers;

use App\Employee;
use App\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\Datatables\Datatables;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        

        return view('employee.index');
    }
    public function employeeData(Request $request)
    {
        if ($request->ajax()) {
            // $data = Employee::join('designations', 'employees.desig_id', '=', 'designations.id')
            // ->select(['employees.empl_id', 'employees.name', 'employees.email', 'employees.image', 'designations.designation']);
            $data=Employee::with('designation', 'designation')->get();
            
            return Datatables::of($data)
                    ->addColumn('designation', function($row){
                        return $row->designation->designation;       
                    })
                    ->addColumn('image', function($row){
                        
                        if (($row->image!=NULL))
                        $status= '<img class="rounded-circle" src="theme/img/undraw_profile.svg" alt="...">';
                        else
                        $status= '<img class="rounded-circle" src="theme/img/undraw_profile.svg" alt="...">';
                        return $status;
            
                    })
                    ->addColumn('action', function($row){
                        
                           $btn = '<a class="btn btn-info btn-sm" href="'. route('employee.show', $row->empl_id) .'">Show</a>
                           <a href="'. route('employee.edit', $row->empl_id) .'" class="edit btn btn-primary btn-sm">edit</a>
                           <a href="javascript:void(0)" class="edit btn btn-danger btn-sm">Delete</a>';
     
                            return $btn;
                    })
                    ->rawColumns(['image','action'])
                    ->make(true);
        }
        // return Datatables::of(Employee::query())->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $designations = DB::table('designations')->get();
        return view('employee.create', compact('designations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => 'required',
            'email' => 'email|unique:users,email',
            'desig_id' => 'required',
        ]);
        DB::beginTransaction();
        try {


            $user            = new User;
            $user->name      = $request->input('name');
            $user->email     = $request->input('email');
            $password_plain=str_random(8);
            
            $user->password  = Hash::make($password_plain);
            $user->save();
           
            Employee::create($request->all()+ ['user_id' =>  $user->id]);
            DB::commit();
            return redirect()->route('employee.index')
                ->with('success', 'Employee Added successfully.');
           

        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('employee.index')
                ->with('error', 'Something went wrong.');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        //
        return view('employee.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee)
    {
        //
        $designations = DB::table('designations')->get();
        return view('employee.edit', compact('employee','designations'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        //
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'desig_id' => 'required',
        ]);

        $employee->update($request->all());

        return redirect()->route('employee.index')
            ->with('success', 'Employee updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        //
        $article->delete();

        return redirect()->route('employee.index')
            ->with('success', 'employee deleted successfully');
    }
}
