<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Departmentmodel;
use App\Models\Configration;
use App\Models\Employee;
use Carbon\Carbon;
use Response;

class Selectdata extends Controller
{ 

    //**start index company-id function
    public function index(){
        $companyid=Configration::select('company_id')->distinct()->get();
        return view('index')->with('companyid',$companyid);
    }


    //**on submition funtion
    public function depart(Request $request){
        $request->all();
        $startdate=$request->startdate;
        $enddate=$request->enddate;
        //dd($startdate);
         $stdate=date('Y-m-d',strtotime($startdate));
        $eddate=date('Y-m-d',strtotime($enddate));
        //dd($stdate);
        $country=$request->country;
        $department=$request->department;
        $employee=$request->employee;
        $cmpid=$request->cmpid;
        $selectedemp=Employee::select('*')
        ->leftjoin('configurations',function($join){
            $join->on('employees.department_id','=','configurations.id');
        })
        ->leftjoin('configurations as config',function($join){
            $join->on('employees.employee_type_id','=','config.id');
        })
         ->leftjoin('companyworklocations',function($join){
            $join->on('employees.work_location_id','=','companyworklocations.id');
         })->select('configurations.value','config.value as evalue','companyworklocations.country','employees.*');
         if($cmpid){
            $selectedemp= $selectedemp->where('employees.company_id',$cmpid);
         }
         if($country) {
            $selectedemp= $selectedemp->where('work_location_id',$country);
         }
         if($department){
            $selectedemp= $selectedemp->where('employees.department_id',$department);
         }
         if($employee){
            $selectedemp= $selectedemp->where('employees.employee_type_id',$employee);
         }
         if($startdate){
            $selectedemp= $selectedemp->whereBetween('start_date',[$stdate,$eddate]);
         }
        return response()->json( $selectedemp->get());
    }

    
    //**on change function
    public function companyid(Request $request){
        $cmpid=$request->cmpid;
        $countryval=Departmentmodel::select('*')->where('company_id',$cmpid)->get();
        $Dvalue=Configration::select('*')->where('company_id',$cmpid)->where('type','departments')->get();
        $Evalue=Configration::select('*')->where('company_id',$cmpid)->where('type','employee-type')->get();
        $result=['countryval'=>$countryval,'dvalue'=>$Dvalue,'evalue'=>$Evalue];
        return response()->json($result);
    }
}
