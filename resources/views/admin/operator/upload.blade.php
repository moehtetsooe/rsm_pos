@extends('admin.header')
@section('css')
<style>
	.btn-default.btn-on.active{background-color: #5BB75B;color: white;}
	.btn-default.btn-off.active{background-color: #DA4F49;color: white;}
	.subject-info-box-1,
	.subject-info-box-2 {
	    float: left;
	    width: 45%;
	    
	    select {
	        height: 400px!important;
	        padding: 0;
	        option {
	            padding: 4px 10px 4px 10px;
	        }
	        option:hover {
	            background: #EEEEEE;
	        }
	    }
	}
	.subject-info-arrows {
	    float: left;
	    width: 10%;
	    input {
	        width: 70%;
	        margin-bottom: 5px;
	    }
	}
</style>
@endsection
@section('content')
<div id="content" class="content">
	<!-- begin breadcrumb -->
	<ol class="breadcrumb pull-right">
		<li><a href="{{url('admin/job-detail/'.$jobAssign->jobid)}}" class="btn btn-success">Back</a></li>
	</ol>
	<!-- end breadcrumb -->
	<!-- begin page-header -->
	<h1 class="page-header">Upload <small>Image</small></h1>
	<div class="row">
		<div class="col-md-12 well">
			<br>
			<div class="row">
				<div class="col-md-8">
					<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/job-detail/upload') }}" enctype="multipart/form-data">
						{{ csrf_field() }}
						<fieldset>
							<div class="form-group">
							    <label for="uploadImage" class="col-md-4 control-label">Upload Image</label>
							    <div class="col-md-6">
							    	<input type="file" id="exampleInputFile" name="uploadImage" class="form-control" required="required">
							    </div>
							</div>
							<div class="form-group">
								<div class="col-md-6 col-md-offset-4 text-right">
									<input type="text" hidden="hidden" name="detailId" value="{{ $id }}">
									<button type="submit" class="btn btn-success">Upload</button>
								</div>
							</div>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('js')
@endsection