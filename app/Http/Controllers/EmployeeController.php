<?php

namespace App\Http\Controllers;

use App\Employee;
use App\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Image;
use Storage;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;

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
            $data = Employee::with('designation', 'designation')->get();

            return Datatables::of($data)
                ->addColumn('designation', function ($row) {
                    return $row->designation->designation;
                })
                ->addColumn('image', function ($row) {

                    if (($row->image != null)) {
                        $status = '<img class="rounded-circle" height="50" width="50"  src="' . asset('storage/images/' . $row->image) . '" alt="...">';
                    } else {
                        $status = '<img class="rounded-circle" src="theme/img/undraw_profile.svg" alt="...">';
                    }

                    return $status;

                })
                ->addColumn('action', function ($row) {

                    $btn = '<a href="' . route('employee.edit', $row->empl_id) . '" class="edit btn btn-primary btn-sm">edit</a>
                           <form action="' . route('employee.destroy', $row->empl_id) . ') }}" method="POST">
                            ' . csrf_field() . '
                            ' . method_field("DELETE") . '
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm(\'Are You Sure Want to Delete?\')">Delete</a>
                            </form>';

                    return $btn;
                })
                ->rawColumns(['image', 'action'])
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
            'image' => 'sometimes|image',
        ]);
        DB::beginTransaction();
        // try {

            $user = new User;
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $password_plain = str_random(8);

            $user->password = Hash::make($password_plain);
            $user->save();

            $emp = Employee::create($request->all() + ['user_id' => $user->id]);
            if ($request->hasfile('image')) {
                $image = $request->file('image');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $location = storage_path('app/public/images/') . $filename;

                Image::make($image)->save($location);

                $emp->image = $filename;
                $emp->save();
            }
            DB::commit();
            $data = [
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => $password_plain,
                
                ];
               Mail::to($data['email'])->send(new WelcomeMail($data));
            return redirect()->route('employee.index')
                ->with('success', 'Employee Added successfully.');

        // } catch (\Throwable $th) {
        //     DB::rollback();
        //     return redirect()->route('employee.index')
        //         ->with('error', 'Something went wrong.');
        // }

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
        return view('employee.edit', compact('employee', 'designations'));
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
            'image' => 'sometimes|image',
        ]);

        $employee->update($request->all());
        if ($request->hasfile('image')) {
            Storage::disk('public')->delete("images/$employee->image");

            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $location = storage_path('app/public/images/') . $filename;

            Image::make($image)->save($location);

            $employee->image = $filename;
            $employee->save();
        }

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

        Storage::disk('public')->delete("images/$employee->image");
        $employee->delete();

        return redirect()->route('employee.index')
            ->with('success', 'employee deleted successfully');
    }
}
