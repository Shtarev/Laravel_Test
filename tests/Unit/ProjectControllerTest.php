<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use App\Models\User;

class ProjectControllerTest extends TestCase
{
    private $userEmail;
    private $userPassword;

    private $projectkObj;

    public function test_project(): void
    {

        $user = User::factory()->create([
            'role' => 1,
            'name' => fake()->name(),
            'email' => $this->userEmail = fake()->safeEmail(),
            'password' => bcrypt($this->userPassword = uniqid())
        ]);

        $jwt_token = $this->jwtToken();
        
        $this->index($jwt_token);

        $id = $this->store($jwt_token);

        $this->show($jwt_token, $id);

        $this->update($jwt_token, $id);

        $this->destroy($jwt_token, $id);

        $user->delete();

    }

    private function index($jwt_token) 
    {
        $index = $this->withHeaders(['Authorization'=>'Bearer ' . $jwt_token])->get('/api/project');
        $this->checkResponse($index);
    }

    private function store($jwt_token) 
    {
        $data = [
            'title' => 'storeTest',
            'description' => 'storeTest',
        ];
        $response = $this->withHeaders(['Authorization'=>'Bearer ' . $jwt_token])->post('api/project/store', $data);
        $this->checkResponse($response, $data);
        $this->projectkObj = $response->getData()->result;

        return $this->projectkObj->id;
    }

    private function show($jwt_token, $id) 
    {
        $data = [
            'title' => $this->projectkObj->title,
            'description' => $this->projectkObj->description
        ];
        $response = $this->withHeaders(['Authorization'=>'Bearer ' . $jwt_token])->get('api/project/show/' . $id);
        $response->assertJsonPath('status', 1);
        $this->checkResponse($response, $data);
    }

    private function update($jwt_token, $id) 
    {
        $data = [
            'title' => 'updateTest',
            'description' => 'updateTest',
        ];
        $response = $this->withHeaders(['Authorization'=>'Bearer ' . $jwt_token])->put('api/project/update/' . $id, $data);
        $this->checkResponse($response, $data);
    }

    private function destroy($jwt_token, $id) 
    {
        $response = $this->withHeaders(['Authorization'=>'Bearer ' . $jwt_token])->delete('api/project/destroy/' . $id);
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
        }
    }
}
