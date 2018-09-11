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

class CheckerController extends Controller
{
    public function index()
    {
        $jobLists = JobAssign::all();
        $arr_job_list = [];
        
        foreach($jobLists as $list){
            $assign_count = JobAssignDetail::where('job_assign_id', $list->id)->count();

            $remain_count = $assign_count - MemberWorkDone::where('job_assigns_id', $list->id)->count();

            $arr_job_list[] = array('id' => $list->id, 'job_code'=>$list->job_code, 'from_date'=> $list->from_date, 'to_date'=> $list->to_date, 'est_time'=>'estimate_complete_time', 'job_assign_count'=>$assign_count, 'remain_count'=>$remain_count);
        }

    	return view('admin.checker.index', compact('arr_job_list'));
    }

    public function detail($id)
    {

    	$upload_id = JobAssign::Where('job_assigns.id','=',$id)->first();
        $uploadedimages = OperatorUpload::where('operator_uploads.job_assign_id','=',$upload_id->id)
        ->select('operator_uploads.*')
        ->get();
        //dd($uploadedimages);
    	return view('admin.checker.detail', compact('uploadedimages'));
    }

    public function ok(Request $request)
    {
        $detailid = $request->detailid;
        $data = [
            'performance' => 'good',
        ];

        MemberWorkDone::where('id','=',$detailid)->update($data);
        return redirect()->back();
        //dd('here');
    }

    public function ng(Request $request)
    {
        $detailid = $request->detailid;
        $data = [
            'performance' => 'notgood',
        ];

        MemberWorkDone::where('id','=',$detailid)->update($data);
        return redirect()->back();
        //dd('here');
    }
}
