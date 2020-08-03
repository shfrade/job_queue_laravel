<?php


namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Submitter extends Model
{
    protected $table = 'submitter';
    protected $primaryKey = 'submitter_id';
    public $timestamps = false;

}
