<?php


namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Processor extends Model
{
    protected $table = 'processor';
    protected $primaryKey = 'processor_id';
    public $timestamps = false;

}
