<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class TaskController extends Controller
{

    private $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }
    /** 
     * GET api/task
     * Display a listing of the resource.
     * Return all Tasks object or info
     */
    public function index(Task $task)
    {
        if($this->user->role == 1) {
            $tasks = $task->all();
        }
        else {
            $tasks = $this->user->task()->get();
        }

        if($tasks->first()) {
            return [
                'status' => 1,
                'result' => $tasks
            ];
        }

        return [
            'status' => 0,
            'result' => 'database is empty'
        ];

    }

    /** 
     * POST api/task/store
     * Store a newly created resource in storage.
     * Return new Task object or info
     */
    public function store(Request $request)
    {

        $data = $request->all();

        $validator = Validator::make($data, [
            'title' => 'required|max:255',
            'description' => 'required|max:255',
            'text' => 'required',
            'deadline' => 'required|date_format:Y-m-d H:i:s|after:now',
            'project_id' => 'required|integer',
            'status' => 'required|in:todo,in_progress,done'
        ]);

        if($validator->fails() === true) {
            return [
                'status' => 0,
                'result' => $validator->messages()
            ];
        }

        if(!Project::find($data['project_id'])) {
            return [
                'status' => 0,
                'result' => 'no project with id = ' . $data['project_id']
            ];
        }

        $task = $this->user->task()->create($data);

        return [
            'status' => 1,
            'result' => $task
        ];
    }

    /** 
     * GET api/task/show/{id}
     * Display the specified resource.
     * Return Task object or info
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

        $task = Task::find($id);

        if(!$task) {
            return [
                'status' => 0,
                'result' => 'no data from id = ' . $id
            ];
        }

        if($this->user->id == $task->user_id || $this->user->role == 1) {
            return [
                'status' => 1,
                'result' => $task
            ];
        }

        return [
            'status' => 0,
            'result' => 'no rights to action'
        ];
    }

    /** 
     * PUT api/task/update/{id}
     * Update the specified resource in storage.
     * Return updated Task object or info
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
            'title' => 'required|max:255',
            'description' => 'required|max:255',
            'text' => 'required',
            'deadline' => 'required|date_format:Y-m-d H:i:s|after:now',
            'project_id' => 'required|integer',
            'status' => 'required|in:todo,in_progress,done'
        ]);

        if($validator->fails() === true) {
            return [
                'status' => 0,
                'result' => $validator->messages()
            ];
        }

        if(!Project::find($data['project_id'])) {
            return [
                'status' => 0,
                'result' => 'no project with id = ' . $data['project_id']
            ];
        }

        $task = Task::find($id);

        if(!$task) {
            return [
                'status' => 0,
                'result' => 'no data from id = ' . $id
            ];
        }

        if($this->user->id == $task->user_id || $this->user->role == 1) {
            $task->update($data);

            return [
                'status' => 1,
                'result' => Task::find($id)
            ];
        }
        
        return [
            'status' => 0,
            'result' => 'no rights to action'
        ];
    }

    /** 
     * DELETE api/task/destroy/{id}
     * Remove the specified resource from storage.
     * Return id of deleted Task or info
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

        $task = Task::find($id);

        if(!$task) {
            return [
                'status' => 0,
                'result' => 'no data from id = ' . $id
            ];
        }

        if($this->user->id == $task->user_id || $this->user->role == 1) {
            $task->delete();
            return [
                'status' => 1,
                'result' => $id
            ];
        }

        return [
            'status' => 0,
            'result' => 'no rights to action'
        ];
    }
}
