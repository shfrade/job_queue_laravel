<?php


namespace App\Http\Controllers;


use App\Submitter;
use App\Job;
use App\Execution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class JobController extends Controller
{

    public function index()
    {
        return Job::all();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'command' => 'required',
                'submitter' => 'required',
                'priority' => 'in:0,1,2,3,4,5',
            ]
        );
        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        // get the submitter by ID or NAME
        $submitter = $this->getSubmitter($request->submitter);
        if ($submitter === false) {
            return response()->json('Submitter not found, please use a valid USERNAME or ID.', 400);
        }

        $job = new Job;
        $job->submitter_id = $submitter->submitter_id;
        $job->command = $request->command;
        if ($request->priority !== null) {
            $job->priority = (string)$request->priority;
        }
        $job->save();

        return Job::find($job->job_id);
    }

    public function update(Request $request, Job $job)
    {

        // Check if the processor is really not executing nothing first.
        $results = DB::select(
            'select count(*) total from execution where job_id = :id',
            ['id' => $job->job_id]
        )[0];
        if ($results->total > 0) {
            return response()->json('Cant jobs that are not on the queue anymore.', 400);
        }

        $execution = Execution::where('job_id', $job->job_id)->first();
//        var_dump($execution);

        $validator = Validator::make($request->all(),
            [
                'priority' => 'in:0,1,2,3,4,5',
            ]
        );
        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        // get the submitter by ID or NAME
        $submitter = $this->getSubmitter($request->submitter);
        if ($submitter === false) {
            return response()->json('Submitter not found, please use a valid USERNAME or ID.', 400);
        }

        $job->submitter_id = $submitter->submitter_id;
        $job->command = $request->command;
        if ($request->priority !== null) {
            $job->priority = (string)$request->priority;
        }

        $job->save();

        return Job::find($job->job_id);
    }

    public function destroy(Job $job)
    {
        // Check if the processor is really not executing nothing first.
        $results = DB::select(
            'select count(*) total from execution where job_id = :id',
            ['id' => $job->job_id]
        )[0];
        if ($results->total > 0) {
            return response()->json('Can only delete jobs that are on queue awaiting to be processed.', 400);
        }

        $job->delete();

        return 'Job deleted.';
    }


    private function getSubmitter($submitter)
    {
        // get the submitter by ID or NAME
        $submitter = Submitter::where('username', $submitter)->orWhere('submitter_id', $submitter)->first();
        if ($submitter == null) {
            return false;
        }
        return $submitter;
    }

    public function show($id)
    {
        $job = Job::find($id);

        if (is_null($job)) {
            return response()->json('Not found.', 404);
        }
        return $job;
    }

    public function detailed($job = null)
    {

        $sql = "select
                    j.job_id,
                    j.priority,
                    s.submitter_id,
                    s.username,
                    p.processor_id,
                    p.hostname,
                    j.created_at job_created_at,
                    e.created_at job_started_at,
                    e.finished_at job_finished_at,
                    case
                        when e.created_at is null then null
                        when e.created_at is not null and e.finished_at is null then
                                    TIMESTAMPDIFF(second,e.created_at,now())
                         when e.created_at is not null and e.finished_at is not null then
                                    TIMESTAMPDIFF(second,e.created_at,e.finished_at)
                    end as job_elapsed_time_seconds,
                    case
                        when e.created_at is null then 'On queue'
                        when e.created_at is not null and e.finished_at is null then 'Processing'
                         when e.created_at is not null and e.finished_at is not null then 'Finished'
                    end as job_status
                from job j
                inner join submitter s on s.submitter_id = j.submitter_id
                left join execution e on j.job_id = e.job_id
                left join processor p on p.processor_id = e.processor_id ";
        if ($job !== null and is_numeric($job)) {
            $sql .= " where j.job_id = " . $job;
        }
        $jobs = DB::select($sql);
        return $jobs;
    }

}
