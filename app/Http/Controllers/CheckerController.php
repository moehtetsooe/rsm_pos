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
    	return view('admin.checker.index', compact('jobLists'));
    }

    public function detail($id)
    {
    	$upload_id = JobAssign::Where('job_assigns.id','=',$id)->first();
    	$uploads = OperatorUpload::Where('operator_uploads.id','=',$upload_id)->get();
    	return view('admin.checker.detail', compact('uploads'));
    }
}
