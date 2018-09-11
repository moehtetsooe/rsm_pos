@extends('admin.header')
@section('css')
<style>
.selectedRole{
	display: none;
}
</style>
@endsection
@section('content')
<div id="content" class="content">
<!-- begin breadcrumb -->
<ol class="breadcrumb pull-right">
<li><a href="{{asset('admin/job-assign')}}" class="btn btn-warning">Back</a></li>

</ol>
<!-- end breadcrumb -->
<!-- begin page-header -->
<h1 class="page-header">Job Assign Finish Page</small></h1>
<div class="row">
<div class="col md-12">
<table  class="table table-striped table-bordered data-table">
<thead>
<tr>
<th>No.</th>
<th>Image Name</th>
<th>Estimate Time</th>
<th>Worked Time</th>
<th>Worked By</th>
<th>Action</th>
</tr>
</thead>
<tbody>
@foreach($job_details as $key => $detail)
<tr>
<td>
{{ $key+1 }}
</td>
<td>{{ $detail->file_name }}</td>
<td>{{ $detail->estimate_complete_time }}</td>
<td>{{ $detail->worked_time }}</td>
<td>{{ $detail->worked_by }}</td>
<td>
<button type="button" class="btn btn-info" id="ok">Yes</button>
<button type="button" class="btn btn-danger">No</button>
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