<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;

class JWTAuthControllerTest extends TestCase
{
    private $data;
    public function test_auth(): void
    {
        $this->data = [
            'name' => 'Andrey',
            'email' => uniqid().'@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 1,
        ];

        $user_auth_data = $this->register();

        $this->logout($user_auth_data['jwt_token']);

        $user_auth_data['jwt_token'] = $this->login();

        $this->getUser($user_auth_data['jwt_token']);

        $this->destroy($user_auth_data['user']['id'], $user_auth_data['jwt_token']);
    }

    private function register()
    {
        $response = $this->json('POST', '/register', $this->data);
        $user = User::find($response->original['user']->id);

        return [
            'jwt_token' => $response->original['token'],
            'user' => $user
        ];
    }

    private function login()
    {
        $response = $this->json('POST', '/login', $this->data);
        return $response->original['token'];
    }

    private function getUser($jwt_token)
    {
        $response = $this->withHeaders(['Authorization'=>'Bearer ' . $jwt_token])->get('/user');
        $this->checkResponse($response, $this->data);
    }

    private function logout($jwt_token)
    {
        $response = $this->withHeaders(['Authorization'=>'Bearer ' . $jwt_token])->post('/logout');
        $this->checkResponse($response);
    }

    private function checkResponse($response, $data = null) 
    {
        if($data !== null) 
        {
            $response->assertJsonPath('user.name', $data['name']);
            $response->assertJsonPath('user.email', $data['email']);
            $response->assertJsonPath('user.role', $data['role']);
        }
        else
        {
            $response->assertJsonPath('message', 'Successfully logged out');
        }
    }

    private function destroy($id, $jwt_token)
    {
        $response = $this->withHeaders(['Authorization'=>'Bearer ' . $jwt_token])->get("/destroy/$id");
        $this->checkDestroyResponse($response, $id);
    }

    private function checkDestroyResponse($response, $id) 
    {
        $response->assertJsonPath('message', "$id");
    }
}