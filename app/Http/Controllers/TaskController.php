<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $task = Task::select('incidents.id', 'incident', 'reported_by', 'risk_level', 'due_date', 'status')
            ->join('incidents', 'tasks.incident_id' , '=', 'incidents.id')
            ->where('tasks.pic', '=', 'Patrik')
            ->orderBy('due_date', 'ASC')
            ->get();
        
        $response = [
            'message' => 'List of task by user login',
            'data' => $task
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'risk_level' => ['required', Rule::in(['low', 'low-to-medium', 'medium', 'medium-to-high', 'high'])],
            'due_date' => 'required|date',
            'incident_id' => 'required',
            'pic' => 'required'
        ]);

        $task = Task::create([
            'risk_level' => $request->input('risk_level'),
            'due_date' => $request->input('due_date'),
            'incident_id' => $request->input('incident_id'),
            'pic' => $request->input('pic')
        ]);

        $response = [
            'message' => 'Task created!',
            'data' => $task
        ];

        return response()->json($response, Response::HTTP_CREATED);
        try {
        } catch (QueryException $e) {
            return response()->json(['message' => "Failed " . $e->errorInfo]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::findOrFail($id);

        return new TaskResource($task);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $request->validate([
            'risk_level' => ['required', Rule::in(['low', 'low-to-medium', 'medium', 'medium-to-high', 'high'])],
            'due_date' => 'required|date',
            'incident_id' => 'required',
            'pic' => 'required'
        ]);

        try {
            $task->update([
                'risk_level' => $request->input('risk_level'),
                'due_date' => $request->input('due_date'),
                'incident_id' => $request->input('incident_id'),
                'pic' => $request->input('pic')
            ]);
    
            $response = [
                'message' => 'Task updated!',
                'data' => $task
            ];
    
            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json(['message' => "Failed " . $e->errorInfo]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);

        try {
            $task->delete();

            $response = [
                'message' => 'Task deleted!'
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json(['message' => "Failed " . $e->errorInfo]);
        }
    }
}
