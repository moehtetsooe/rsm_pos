<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\JobAssign;
use App\JobAssignDetail;
use App\JobAssignOperator;
use App\MemberWorkDone;
use App\OperatorUploads;
use App\Admin;
use App\Role;
use Auth;
use DB;
use File;
use Carbon\Carbon;

class OperatorController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    
    public function index()
    {
        $currentuserid = Auth::guard('admin')->user()->role;
        $role = Role::Where('id', $currentuserid)->value('role');
        
        if($currentuserid == 1){
            $arr_job_list = JobAssign::all()->toArray();
        }else{
            $jobLists = JobAssign::all();
            $arr_job_list = [];
            foreach($jobLists as $list){
                $operator_ids = $list->operator_id;
                foreach($operator_ids as $id){
                    if ($id == $currentuserid) {
                        $arr_job_list[] = array('id' => $list->id, 'job_code'=>$list->job_code, 'from_date'=> $list->from_date, 'to_date'=> $list->to_date, 'est_time'=>'estimate_complete_time', 'operator_id'=> $id);    
                    }
                }     
            }
        }
        
        return view('admin.operator.index', compact('arr_job_list'));
    }
    
    public function detail($id)
    {
        
        $jobDetails = JobAssignDetail::Where('job_assign_details.job_assign_id','=',$id)->get();
        $status = MemberWorkDone::get();
        return view('admin.operator.detail', compact('jobDetails','status'));
    }
    
    public function download(Request $request)
    {
        $user_id = Auth::guard('admin')->user()->id;
        $user_role = Auth::guard('admin')->user()->role;
        $jobDetailId = $request->id;
        $operatorId = Role::Where('roles.id','=',$user_role)->select('roles.id')->first();
        $download_time = Carbon::now();
        
        $data = [
            'user_id' => $user_id,
            'user_role' => $user_role,
            'job_assign_details_id' => $jobDetailId,
            'job_assign_operators_id' => $operatorId->id,
            'status' => 'downloaded',
            'download_time' => $download_time,
        ];
        
        MemberWorkDone::create($data);
        
        return $data;
    }
}
