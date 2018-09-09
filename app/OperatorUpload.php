<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class OperatorUpload extends Model
{
    protected $table = 'operator_uploads';
    protected $fillable = ['user_id','job_assign__id','job_assign_details_id','job_assign_operator_id','file_path','file_name','deleted_at'];
    use SoftDeletes;
}
