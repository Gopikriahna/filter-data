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
        // $country=Departmentmodel::select('*')->get()->unique('country');
        $companyid=Configration::select('company_id')->distinct()->get();
        // $country=Departmentmodel::select('country','country_code')->distinct()->get();
        // $department=Configration::select('value')->where('type','=','departments')->get();
        // $employee=Configration::select('value')->where('type','=','employee-type')->get();
        // $users = Departmentmodel::join('Configration', 'Configration.id', '=', 'Departmentmodel.id')
        // ->join('Employee', 'Employee.id', '=', 'Configration.id')
        // ->get();
        // dd($users);
        return view('index')->with('companyid',$companyid);
        // ->with('department',$department)->with('country',$country)
        // ->with('employee',$employee);
    }
    //demo function
    public function tabledata(){
        return view('selectdat.selectedemp');
    }


    //**on submition funtion
    public function depart(Request $request){
        $request->all();
        $startdate=$request->startdate;
        $enddate=$request->enddate;
         $stdate=date('Y-m-d',strtotime($startdate));
        
        $eddate=date('Y-m-d',strtotime($enddate));
         //dd($enddate);
           //dd($eddate);
        //  dd($stdate);
        $country=$request->country;
        $department=$request->department;
        $employee=$request->employee;
        $cmpid=$request->cmpid;
        //  $Eid=Departmentmodel::select('id')->where('country_code',$country)->get();
        //  $Did=Configration::select('company_id')->where('value',$department)->get();
        //  $Mid=Configration::select('company_id')->where('value',$employee)->get();
         



        
        
        // $sDate = Carbon::createFromFormat('m-d-y', $stdate)->format('Y-m-d');;
                                   
        // $eDate = Carbon::createFromFormat('m-d-y', $eddate)->format('Y-m-d');;
                                    
    
        // dd($request->sdate);
        //  dd($data['sdate']);
        //   $selectedemp=Employee::select('*')->where('employees.company_id',$cmpid)
            // ->whereBetween('start_date',[$stdate,$eddate])
         
        
        
        //     ->leftjoin('companyworklocations',function($join){
        //      $join->on('employees.work_location_id','=','companyworklocations.id');})
        //     //  ->joinSub($department,'department',function($join){
        //     //     $join->on('employees.company_id','=','department.company_id');
        //     //  })
        //  ->select('employees.*','companyworklocations.country')
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
        //  ->where('work_location_id',$country)->where('employees.employee_type_id',$employee)
        //  ->where('employees.department_id',$department)
        
        //  return $selectedemp;
        //  return view('emp')->with('selectedemp',$selectedemp);
        // dd($selectedemp);
        return response()->json( $selectedemp->get());
        // return $selectedemp;

    }


    //demo check funtion
    public function country(Request $request){
        $country=$request->country();
        
        $country=Departmentmodel::select('*')->where('country_code',$country)->get();
        return response()->json( $country);
    }
    public function joinsd(){
        $twotables=Employee::join('configurations',function ($join) {
            $join->on("configurations.id", "=", "employees.department_id");
        })->get();
       
        return $twotables;
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
