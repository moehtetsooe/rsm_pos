@extends('admin.header')
@section('css')
<style>
	.btn-default.btn-on.active{background-color: #5BB75B;color: white;}
	.btn-default.btn-off.active{background-color: #DA4F49;color: white;}

	.upload-btn-wrapper {
	  position: relative;
	  overflow: hidden;
	  display: inline-block;
	}

	.upload-btn-wrapper input[type=file] {
	  font-size: 100px;
	  position: absolute;
	  left: 0;
	  top: 0;
	  opacity: 0;
	}

	.disabled {
		cursor: no-drop;
	 	pointer-events: none;
	}
</style>
@endsection
@section('content')
<div id="content" class="content">
	<ol class="breadcrumb pull-right">
		<li><a href="{{asset('admin/operator')}}" class="btn btn-warning">Back</a></li>
	</ol>
	<h1 class="page-header">Assigned <small>Job Lists</small></h1>
	<div class="row">
		<div class="col-md-12">
			<table  class="table table-striped table-bordered data-table">
				<thead>
					<tr>
						<th>No.</th>
						<th>Photo Name</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					@foreach($uploads as $key => $detail)
					<tr>
						<td>
							{{ $key+1 }}
						</td>
						<td>{{ $detail->file_name }}</td>
						<td id="">
							<a download="{{ $detail->file_name }}" href="{{ $detail->file_path }}/{{ $detail->file_name }}" title="ImageName" class="btn btn-success" style="position: relative;top: -14px;" id="" value="">
							    Download
							</a>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection
@section('js')
@endsection