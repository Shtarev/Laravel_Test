<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use App\Models\User;
use App\Models\Project;
use Carbon\Carbon;

class TaskControllerTest extends TestCase
{
    private $userEmail;
    private $userPassword;
    private $taskObj;
    private $userId;
    private $projectId;
    private $deadlineDate;

    public function test_task(): void
    {

        $user = User::factory()->create([
            'role' => random_int(1, 2),
            'name' => fake()->name(),
            'email' => $this->userEmail = fake()->safeEmail(),
            'password' => bcrypt($this->userPassword = uniqid())
        ]);

        $project = Project::factory()->createForTaskTests();

        $this->deadlineDate = Carbon::now()->addDays(5);

        $this->userId = $user->id;

        $this->projectId = $project->id;

        $jwt_token = $this->jwtToken();
        
        $this->index($jwt_token);

        $id = $this->store($jwt_token);

        $this->show($jwt_token, $id);

        $this->update($jwt_token, $id);

        $this->destroy($jwt_token, $id);

        $user->delete();

        $project->delete();
    }

    private function index($jwt_token) 
    {
        $response = $this->withHeaders(['Authorization'=>'Bearer ' . $jwt_token])->get('/api/task');
        $this->checkResponse($response);
    }

    private function store($jwt_token) 
    {
        $data = [
            'title' => 'storeTest',
            'description' => 'storeTest',
            'text' => 'storeTest',
            'deadline' => $this->deadlineDate->toDateTimeString(),
            'project_id' => $this->projectId,
            'status' => 'todo'
        ];

        $response = $this->withHeaders(['Authorization'=>'Bearer ' . $jwt_token])->post('api/task/store', $data);

        $this->checkResponse($response, $data);
        $this->taskObj = $response->getData()->result;

        return $this->taskObj->id;
    }

    private function show($jwt_token, $id) 
    {
        $data = [
            'id' => $this->taskObj->id,
            'title' => $this->taskObj->title,
            'description' => $this->taskObj->description,
            'text' => $this->taskObj->text,
            'deadline' => $this->taskObj->deadline,
            'project_id' => $this->taskObj->project_id,
            'status' => $this->taskObj->status
        ];

        $response = $this->withHeaders(['Authorization'=>'Bearer ' . $jwt_token])->get('api/task/show/' . $id);
        $response->assertJsonPath('status', 1);
        $this->checkResponse($response, $data);
    }

    private function update($jwt_token, $id) 
    {
        $data = [
            'title' => 'updateTest',
            'description' => 'updateTest',
            'text' => 'updateTest',
            'deadline' => $this->deadlineDate->addDays(5)->toDateTimeString(),
            'project_id' => $this->projectId,
            'status' => 'in_progress'
        ];
        $response = $this->withHeaders(['Authorization'=>'Bearer ' . $jwt_token])->put('api/task/update/' . $id, $data);
        $this->checkResponse($response, $data);
    }

    private function destroy($jwt_token, $id) 
    {
        $response = $this->withHeaders(['Authorization'=>'Bearer ' . $jwt_token])->delete('api/task/destroy/' . $id);
        $response->assertJsonPath('status', 1);
        $response->assertJsonPath('result', "$id");
    }

    private function jwtToken() 
    {
        $response = $this->json('POST', '/login', ['email' => $this->userEmail, 'password' => $this->userPassword]);
        return $response->original['token'];
    }

    private function checkResponse($response, $data = null) 
    {
        $result = ($data) ? 'result' : 'result.0';

        if($response->getData()->status !== 0){
            $response->assertJson(fn (AssertableJson $json) =>
                $json->has('status')
                ->has($result, fn (AssertableJson $json) =>
                $json->whereAllType([
                    'id' => 'integer',
                    'title' => 'string',
                    'description' => 'string',
                    'text' => 'string',
                    'user_id' => 'integer',
                    'deadline' => 'string',
                    'project_id' => 'integer',
                    'status' => 'string'
                ])
                ->etc()
                )
            );
        }
        else 
        {
            $response->assertJson(fn (AssertableJson $json) =>
                $json->has('status')
                ->has('result')
            );
        }

        if($data !== null) 
        {
            $response->assertJsonPath('status', 1);
            $response->assertJsonPath('result.title', $data['title']);
            $response->assertJsonPath('result.description', $data['description']);
            $response->assertJsonPath('result.text', $data['text']);
            $response->assertJsonPath('result.user_id', $this->userId);
            $response->assertJsonPath('result.deadline', $data['deadline']);
            $response->assertJsonPath('result.project_id', $data['project_id']);
            $response->assertJsonPath('result.status', $data['status']);
        }
    }
}
