<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = Company::select('id', 'name')->get();
        return view('layouts.employee.index', compact('company'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $vData = $request->validate([
            'first_name' => 'required|string|max:199',
            'last_name' => 'required|string|max:199',
            'email' => 'nullable|string|email|max:199',
            'phone' => 'nullable|string|max:199',
            'company_id' => 'required|exists:companies,id',
        ]);
        
        return Employee::create($vData);
    }

    public function getEmployeeData(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');
        $search = $request->input('search');
        $order = $request->input('order');
        $columnsNameArray = $request->input('columns');
        $columnIndex = $order[0]['column'];
        $columnName = $columnsNameArray[$columnIndex]['data'];
        $columnSortOrder = $order[0]['dir'];
        $columnSearchValue = $search['value'];

        $company = Employee::skip($start)->take($length)->when($search, function($q) use($search){
            $term = "%{$search['value']}%";
            $q->where(DB::raw('CONCAT(first_name, " ", last_name)'), 'LIKE', $term);
            $q->orWhere('email', 'like', $term);
        })->with('company:id,name')->orderBy($columnName, $columnSortOrder)->get();

        $companyData = array();
        foreach ($company as $c){
            $c->full_name = $c->first_name . ' ' . $c->last_name;
            $c->company_id = $c->company? $c->company->id : '';
            $c->company_name = $c->company? $c->company->name : '';
            array_push($companyData, $c);
        }
        
        $comp = new Employee();
        $recordsTotal = $comp->count();

        $recordsFiltered = $comp->when($search, function($q) use($search){
            $term = "%{$search['value']}%";
            $q->where(DB::raw('CONCAT(first_name, " ", last_name)'), 'LIKE', $term);
            $q->orWhere('email', 'like', $term);
        })->count();
        
        $data = [
            'draw' => intval($draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $companyData,
        ];
        
        return response()->json($data);
    }
    public function getCompany()
    {
        return Company::select('id', 'name')->get();
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        
        $vData = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'first_name' => 'required|string|max:199',
            'last_name' => 'required|string|max:199',
            'email' => 'nullable|string|email|max:199',
            'phone' => 'nullable|string|max:199',
            'company_id' => 'required|exists:companies,id',
        ]);

        $emp_id = $vData['employee_id'];
        unset($vData['employee_id']);
        $employee = Employee::find($emp_id);
        $employee->update($vData);
        return $employee;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
         $employee->delete();
        return response()->json(['msg' => 'Deleted Successfully'], 200);
    }
}
