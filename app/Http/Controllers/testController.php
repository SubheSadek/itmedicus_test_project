<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;

class testController extends Controller
{
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getData(Request $request)
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

        $company = Company::skip($start)->take($length)->when($search, function($q) use($search){
            $q->where('name', 'like', '%'.$search['value'].'%');
            $q->orWhere('email', 'like', '%'.$search['value'].'%');
            $q->orWhere('website', 'like', '%'.$search['value'].'%');
        })->orderBy($columnName, $columnSortOrder)->get();
        
        $comp = new Company();
        $recordsTotal = $comp->count();

        $recordsFiltered = $comp->when($search, function($q) use($search){
            $q->where('name', 'like', '%'.$search['value'].'%');
            $q->orWhere('email', 'like', '%'.$search['value'].'%');
            $q->orWhere('website', 'like', '%'.$search['value'].'%');
        })->count();
        
        $data = [
            'draw' => intval($draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $company
        ];
        
        return response()->json($data);
    }
}
