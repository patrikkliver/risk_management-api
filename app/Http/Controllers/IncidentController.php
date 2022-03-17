<?php

namespace App\Http\Controllers;

use App\Http\Resources\IncidentResource;
use App\Models\Incident;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class IncidentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $incident = Incident::select('incidents.id', 'incident', 'reported_by', 'risk_level', 'status')
            ->join('tasks', 'incidents.id', '=', 'tasks.incident_id')
            ->orderBy('incidents.created_at', 'DESC')
            ->get();

        $response = [
            'message' => 'List incident order by created date',
            'data' => $incident
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
            'incident' => 'required',
            'reported_by' => 'required',
            'date_incident' => 'required|date',
            'position' => 'required',
            'recommendation' => 'required',
            'cause' => ['required', Rule::in(['APD', 'Environment', 'Posisi Kerja', 'Peralatan', 'Prosedur', 'Reaksi Pekerja'])],
            'system' => ['required', Rule::in(['Electrical', 'Instrument', 'Civil', 'Safety', 'Security'])],
            'source' => ['required', Rule::in(['Near Miss', 'Unsafe Activity', 'Unsafe Condition', 'Tinjauan Management', 'Keluhan Masyarakat', 'Internal Audit', 'Eksternal Audit'])],
        ]);

        try {
            $incident = Incident::create([
                'incident' => $request->input('incident'),
                'reported_by' => $request->input('reported_by'),
                'date_incident' => $request->input('date_incident'),
                'position' => $request->input('position'),
                'recommendation' => $request->input('recommendation'),
                'description' => $request->input('description'),
                'cause' => $request->input('cause'),
                'system' => $request->input('system'),
                'source' => $request->input('source')
            ]);
    
            $response = [
                'message' => 'Incident created!',
                'data' => $incident
            ];
    
            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $e) {
            return response()->json([
                'message' => "Failed " . $e->errorInfo
            ]);
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
        $incident = Incident::findOrFail($id);

        return new IncidentResource($incident);
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
        $incident = Incident::findOrFail($id);

        $request->validate([
            'incident' => 'required',
            'reported_by' => 'required',
            'date_incident' => 'required|date',
            'position' => 'required',
            'recommendation' => 'required',
            'cause' => ['required', Rule::in(['APD', 'Environment', 'Posisi Kerja', 'Peralatan', 'Prosedur', 'Reaksi Pekerja'])],
            'system' => ['required', Rule::in(['Electrical', 'Instrument', 'Civil', 'Safety', 'Security'])],
            'source' => ['required', Rule::in(['Near Miss', 'Unsafe Activity', 'Unsafe Condition', 'Tinjauan Management', 'Keluhan Masyarakat', 'Internal Audit', 'Eksternal Audit'])],
        ]);

        try {
            $incident->update([
                'incident' => $request->input('incident'),
                'reported_by' => $request->input('reported_by'),
                'date_incident' => $request->input('date_incident'),
                'position' => $request->input('position'),
                'recommendation' => $request->input('recommendation'),
                'description' => $request->input('description'),
                'cause' => $request->input('cause'),
                'system' => $request->input('system'),
                'source' => $request->input('source')
            ]);
    
            $response = [
                'message' => 'Incident updated!',
                'data' => $incident
            ];
    
            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json([
                'message' => "Failed " . $e->errorInfo
            ]);
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
        $incident = Incident::findOrFail($id);

        try {
            $incident->delete();

            $response = [
                'message' => 'Incident deleted!',
                'date' => $incident
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json([
                'message' => "Failed " . $e->errorInfo
            ]);
        }
    }

    public function new_incident()
    {
        $incidents = Incident::select('incidents.id', 'incident', 'reported_by', 'date_incident', 'position')
            ->leftjoin('tasks', 'incidents.id', '=', 'tasks.incident_id')
            ->where('tasks.incident_id', null)
            ->get();
        
        $response = [
            'message' => 'List of New Incident',
            'data' => $incidents
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    public function is_done()
    {
        $incidents = Incident::select('incidents.id', 'incident', 'reported_by', 'risk_level', 'due_date')
            ->join('tasks', 'incidents.id', '=', 'tasks.incident_id')
            ->where('tasks.is_done', '=', 1)
            ->get();
        
        $response = [
            'message' => 'List of incident done',
            'data' => $incidents
        ];

        return response()->json($response, Response::HTTP_OK);
    }
}
