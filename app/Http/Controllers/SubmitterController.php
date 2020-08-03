<?php


namespace App\Http\Controllers;


use App\Submitter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SubmitterController extends Controller
{

    public function index()
    {
        return Submitter::all();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), ['username' => 'required|unique:submitter,username|max:200']);
        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }
        $submitter = new Submitter;
        $submitter->username = $request->username;
        $submitter->save();
        return Submitter::find($submitter->submitter_id);
    }

    public function show($id)
    {
        $job = Submitter::find($id);

        if (is_null($job)) {
            return response()->json('Not found.', 404);
        }
        return $job;
    }

    public function update(Request $request, Submitter $submitter)
    {
        $validator = Validator::make($request->all(), ['username' => 'required|unique:submitter,username,' . $submitter->submitter_id . ',submitter_id|max:200']);
        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $submitter->username = $request->username;
        $submitter->save();

        return Submitter::find($submitter->submitter_id);
    }

    public function destroy(Submitter $submitter)
    {
        //if the submitter is linked to ANY job, he can't be removed.
        $results = DB::select(
            'select count(*) total from job where submitter_id = :id',
            ['id' => $submitter->submitter_id]
        )[0];
        if ($results->total > 0) {
            return response()->json('This submitter cant be deleted.', 400);
        }
        $submitter->delete();

        return 'Submitter deleted.';
    }
}
