<?php


namespace App\Http\Controllers;


use App\Execution;
use App\Processor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProcessorController extends Controller
{

    public function index()
    {
        return Processor::all();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), ['hostname' => 'required|unique:processor,hostname|max:64']);
        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }
        $processor = new Processor;
        $processor->hostname = $request->hostname;
        $processor->save();
        return Processor::find($processor->processor_id);
    }

    public function update(Request $request, Processor $processor)
    {
        $validator = Validator::make($request->all(), ['hostname' => 'required|unique:processor,hostname,' . $processor->processor_id . ',processor_id|max:200']);
        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $processor->hostname = $request->hostname;
        $processor->save();

        return Processor::find($processor->processor_id);
    }

    public function destroy(Processor $processor)
    {
        // Check if the processor is really not executing nothing first.
        $results = DB::select(
            'select count(*) total from execution where processor_id = :id',
            ['id' => $processor->processor_id]
        )[0];
        if ($results->total > 0) {
            return response()->json('This processor cant be deleted.', 400);
        }

        $processor->delete();

        return 'Processor deleted.';
    }

    public function show($id)
    {
        $job = Processor::find($id);

        if (is_null($job)) {
            return response()->json('Not found.', 404);
        }
        return $job;
    }

    public function nextJob(Processor $processor)
    {
        // Check if the processor is really not executing nothing first.
        $jobs_running = DB::select(
            'select count(*) total from execution where finished_at is null and processor_id = :id',
            ['id' => $processor->processor_id]
        )[0];
        if ($jobs_running->total > 0) {
            return response()->json('Processor already executing a job.', 400);
        }
        //  get next job that isn't processing
        $next_job = DB::select(
            'select j.job_id from job j where j.job_id not in (select e.job_id from execution e) order by j.priority, created_at limit 0,1;'
        );
        if (count($next_job) == 0) {
            return response()->json('No job on the queue.', 400);
        }
        $job_id = $next_job[0]->job_id;
        DB::insert(
            'insert into execution (job_id, processor_id) values (:job, :processor)',
            ['job' => $job_id, 'processor' => $processor->processor_id]
        );

        return Execution::where('job_id', $job_id)->where('processor_id', $processor->processor_id)->first();
    }


    public function finishJob(Processor $processor)
    {
        // Check if the processor is really  executing something first.
        $jobs_running = DB::select(
            'select count(*) total from execution where finished_at is null and processor_id = :id',
            ['id' => $processor->processor_id]
        )[0];
        if ($jobs_running->total == 0) {
            return response()->json('This processor doens`t have a executing a job.', 400);
        }
        // finish the actual job.
        DB::update(
            'update execution set finished_at = now() where finished_at is null and processor_id = :id',
            ['id' => $processor->processor_id]
        );
        return Execution::where('processor_id', $processor->processor_id)->orderBy('finished_at', 'DESC')->first();
    }

}
