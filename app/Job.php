<?php


namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Job extends Model
{
    protected $table = 'job';
    protected $primaryKey = 'job_id';
    public $timestamps = false;

     public function submitter()
     {
         return $this->hasOne('App\Submitter', 'submitter_id', 'submitter_id');
     }

     public function status(){
         // On Queue, Processing, Finished

     }
}
