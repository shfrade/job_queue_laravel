<?php


namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Execution extends Model
{
    protected $table = 'execution';
    protected $primaryKey = ['job_id','processor_id'];
    public $timestamps = false;
    public $incrementing = false;

    public function job()
    {
        return $this->hasOne('App\Job', 'job_id', 'job_id');
    }

    public function processor()
    {
        return $this->hasOne('App\Processor', 'processor_id', 'processor_id');
    }
}
