<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    /** 
     * GET api/project
     * Display a listing of the resource.
     * Return all Projects object or info
     */
    public function index(Project $project)
    {
        $projects = $project->all();

        if($projects->first()) {
            return [
                'status' => 1,
                'result' => $projects
            ];
        }

        return [
            'status' => 0,
            'result' => 'database is empty'
        ];
    }

    /** 
     * POST api/project/store
     * Store a newly created resource in storage.
     * Return new Project object or info
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required'
        ]);

        if($validator->fails() === true) {
            return [
                'status' => 0,
                'result' => $validator->messages()
            ];
        }
        
        $id = Project::create($request->all())->id;
        return [
            'status' => 1,
            'result' => Project::find($id)
        ];
    }

    /** 
     * GET api/project/show/{id}
     * Display the specified resource.
     * Return Project object or info
     */
    public function show(string $id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer'
        ]);

        if($validator->fails() === true) {
            return [
                'status' => 0,
                'result' => $validator->messages()
            ];
        }

        $project = Project::find($id);

        if(!$project) {
            return [
                'status' => 0,
                'result' => 'no data from id = ' . $id
            ];
        }

        return [
            'status' => 1,
            'result' => $project
        ];
    }

    /** 
     * PUT api/project/update/{id}
     * Update the specified resource in storage.
     * Return updated Project object or info
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer'
        ]);

        if($validator->fails() === true) {
            return [
                'status' => 0,
                'result' => $validator->messages()
            ];
        }

        $data = $request->all();

        $validator = Validator::make($data, [
            'title' => 'required',
            'description' => 'required',
        ]);

        if($validator->fails() === true) {
            return [
                'status' => 0,
                'result' => $validator->messages()
            ];
        }

        $project = Project::find($id);

        if(!$project) {

            return [
                'status' => 0,
                'result' => 'no data from id = ' . $id
            ];
        }

        $project->update([
            'title' => $data['title'],
            'description' => $data['description']
        ]);

        return [
            'status' => 1,
            'result' => Project::find($id)
        ];
    }

    /** 
     * DELETE api/project/destroy/{id}
     * Remove the specified resource from storage.
     * Return id of deleted Project or info
     */
    public function destroy(string $id)
    {

        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer'
        ]);

        if($validator->fails() === true) {
            return [
                'status' => 0,
                'result' => $validator->messages()
            ];
        }

        $project = Project::find($id);

        if(!$project) {
            return [
                'status' => 0,
                'result' => 'no data from id = ' . $id
            ];
        }

        $project->delete();
        
        return [
            'status' => 1,
            'result' => $id
        ];
    }
}
