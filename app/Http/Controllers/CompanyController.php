<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    // use Media;

    public function index(Request $request)
    {
        return view('layouts.company.index');
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

    public function uploads($file)
    {
        if($file) {

            $fileName   = time() . $file->getClientOriginalName();
            Storage::disk('public')->put($fileName, File::get($file));
            
            return storage_path('app/public/' . $fileName);
        }
    }

    public function formateStoreFile($path){
        // $path = storage_path('app/public/1650977418myimagejpg.jpg');
        $data = file_get_contents($path);
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $base64 = base64_encode($data);
        $src = 'data:image/' . $type . ';base64,' . $base64;
        return $src;
    }

    public function store(Request $request)
    {
        

       $vData = $request->validate([
            'name' => 'required|string|max:199',
            'email' => 'nullable|string|email|max:199',
            'website' => 'nullable|string|max:199',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        if($file = $request->file('logo')){
            $fileData = $this->uploads($file,'');
            $vData['logo'] = $fileData;
        }

        return Company::create($vData);
        

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // return $request->all();
        $vData = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|string|max:199',
            'email' => 'nullable|string|email|max:199',
            'website' => 'nullable|string|max:199',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ]);
        
        $company_id = $vData['company_id'];
        unset($vData['company_id']);

        $check = Company::where('id', $company_id)->first();

        if($file = $request->file('logo')){
            if($check->logo && file_exists($check->logo)){
                unlink($check->logo);
            }
            $fileData = $this->uploads($file,'');
            $vData['logo'] = $fileData;
        }

        $company = Company::where('id', $company_id)->update($vData);

        if($company){
            return Company::where('id', $company_id)->first();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $company = Company::where('id', $id)->first();

        if($company && $company->logo && file_exists($company->logo)){
            unlink($company->logo);
        }

        $company->delete();
        if($company){
            return response()->json(['msg' => 'Deleted Successfully'], 200);
        }
    }

    public function getCompanyData(Request $request)
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

        $companyData = array();
        foreach ($company as $c){
            $c->logo = $c->logo ? $this->formateStoreFile($c->logo) : '';
            array_push($companyData, $c);
        }
        
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
            'data' => $companyData
        ];
        
        return response()->json($data);
    }
}
