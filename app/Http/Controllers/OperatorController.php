<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\JobAssign;
use App\JobAssignDetail;
use App\JobAssignOperator;
use App\MemberWorkDone;
use App\OperatorUpload;
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

        $jobLists = JobAssign::all();
        $arr_job_list = [];
        
        foreach($jobLists as $list){
            $assign_count = JobAssignDetail::where('job_assign_id', $list->id)->count();
            $remain_count = $assign_count - MemberWorkDone::where('job_assigns_id', $list->id)->count();
            if($currentuserid == 1){
                $arr_job_list[] = array('id' => $list->id, 'job_code'=>$list->job_code, 'from_date'=> $list->from_date, 'to_date'=> $list->to_date, 'est_time'=>'estimate_complete_time', 'job_assign_count'=>$assign_count, 'remain_count'=>$remain_count);
            }else{
                $operator_ids = $list->operator_id;            
                foreach($operator_ids as $id){
                    if ($id == $currentuserid) {
                        $arr_job_list[] = array('id' => $list->id, 'job_code'=>$list->job_code, 'from_date'=> $list->from_date, 'to_date'=> $list->to_date, 'est_time'=>'estimate_complete_time', 'job_assign_count'=>$assign_count, 'remain_count'=>$remain_count);
                    }
                }    
            }
        }
        return view('admin.operator.index', compact('arr_job_list'));
    }
    
    public function detail($id)
    {
        $jobDetails = JobAssignDetail::join('job_assigns','job_assigns.id','=','job_assign_details.job_assign_id')
        ->Where('job_assign_details.job_assign_id','=',$id)
        ->select('job_assign_details.*','job_assigns.created_by as create')
        ->get();
        $status = MemberWorkDone::get();
        return view('admin.operator.detail', compact('jobDetails','status'));
    }
    
    public function download(Request $request)
    {
        $user_id = Auth::guard('admin')->user()->id;
        $user_role = Auth::guard('admin')->user()->role;
        $jobDetailId = $request->id;
        $createdby = $request->createdby;
        $operatorId = Role::Where('roles.id','=',$user_role)->select('roles.id')->first();
        $download_time = Carbon::now();
        
        $data = [
            'user_id' => $user_id,
            'user_role' => $user_role,
            'job_assigns_id' => $request->createdby,
            'job_assign_details_id' => $jobDetailId,
            'job_assign_operators_id' => $operatorId->id,
            'status' => 'downloaded',
            'download_time' => $download_time,
        ];
        
        MemberWorkDone::create($data);
        
        return $data;
    }

    public function imageUpload($id)
    {
        $jobAssign = jobAssign::join('job_assign_details','job_assign_details.job_assign_id','=','job_assigns.id')
        ->Where('job_assign_details.id','=',$id)
        ->select('job_assigns.id as jobid')
        ->first();
        return view('admin.operator.upload', compact('id','jobAssign'));
    }

    public function upload(Request $request)
    {
        $validatedData = $request->validate([
            'detailId' => 'required',
            'uploadImage' => 'required',
        ]);

        //dd($request->detailId);
        $user_role = Auth::guard('admin')->user()->role;
        $detailId = $request->detailId;
        $detail = JobAssignDetail::Where('id',$detailId)->first();
        $jobAssign = JobAssign::Where('id',$detail->job_assign_id)->first();

        DB::beginTransaction();
        
        try {
            
            if ($request->uploadImage != null) {
                $files = $request->uploadImage;
                $file_path = date("M-Y");
                $destinationPath =app()->make('path.public');
                $image_path = '/uploads/operatoruploads/'.$file_path;
                $filename= str_random(3).'cover_'.$files->getClientOriginalName();
                $files->move($destinationPath.$image_path, $filename);

                /*************resize images**************** */            
                // ImageSize::make($destinationPath.'/'.$image_path.'/'.$filename)->resize(600, 320)->save($destinationPath.'/'.$image_path.'/'.$filename);  
                /***************end resize images***************/

                $image_name = $filename;
                $image_path = $image_path;
            }

            $upload = new OperatorUpload;
            $upload->user_id = Auth::guard('admin')->user()->id;
            $upload->job_assign_id = $jobAssign->id;
            $upload->job_assign_details_id = $detailId;
            $upload->job_assign_operator_id = $user_role;
            $upload->file_path = $image_path;
            $upload->file_name = $image_name;
            $upload->save();

            $job_assign_id = $upload->job_assigns_id;
            $job_assign_details_id = $upload->job_assign_details_id;

            MemberWorkDone::Where('member_work_dones.job_assigns_id','=',$job_assign_id)
            ->Where('member_work_dones.job_assign_details_id','=',$job_assign_details_id)
            ->update([
                'status' => 'uploaded',
            ]);
                
            DB::commit();
                
            return redirect('admin/job-detail/$jobAssign->id')->with('success', 'Successfully Upload Image!');
                
        } catch (Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }

    }
}
