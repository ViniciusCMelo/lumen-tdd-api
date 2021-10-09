<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class UserTest extends TestCase {
    use DatabaseTransactions;

    /**
     * A User test.
     *
     * @return void
     */
    public function testCreateUser() {
        $data = [
            'name' => 'Nome 01' . date('Ymdis') . rand(1, 100),
            'email' => 'email3@exemplo.com',
            'password' => '123',
            'password_confirmation' => '123'

        ];
        $this->post('/api/user', $data);

        $this->assertResponseOk();

        $response = (array)json_decode($this->response->content());

        $this->assertArrayHasKey('name', $response);
        $this->assertArrayHasKey('email', $response);
        $this->assertArrayHasKey('id', $response);

        $this->seeInDatabase('users', [
            'name' => $data['name'],
            'email' => $data['email']
        ]);

    }

    public function testViewUser() {
        $user = \App\Models\User::first();
        $this->get('/api/user/' . $user->id);
        $this->assertResponseOk();
        $response = (array)json_decode($this->response->content());
        $this->assertArrayHasKey('name', $response);
        $this->assertArrayHasKey('email', $response);
        $this->assertArrayHasKey('id', $response);
    }

    public function testUpdateUserNoPassword() {
        $user = \App\Models\User::first();

        $data = [
            'name' => 'Nome 01' . date('Ymdis') . rand(1, 100),
            'email' => 'email3' . date('Ymdis') . rand(1, 100) . '@exemplo.com',
        ];

        $this->put('/api/user/' . $user->id, $data);
        echo $this->response->content();
        $this->assertResponseOk();

        $response = (array)json_decode($this->response->content());

        $this->assertArrayHasKey('name', $response);
        $this->assertArrayHasKey('email', $response);
        $this->assertArrayHasKey('id', $response);

        $this->notSeeInDatabase('users', [
            'name' => $user->name,
            'email' => $user->email,
            'id' => $user->id
        ]);

    }

    public function testUpdateUserWithPassword() {
        $user = \App\Models\User::first();

        $data = [
            'name' => 'Nome 01' . date('Ymdis') . rand(1, 100),
            'email' => 'email3' . date('Ymdis') . rand(1, 100) . '@exemplo.com',
            'password' => '123',
            'password_confirmation' => '123'
        ];

        $this->put('/api/user/' . $user->id, $data);

        $this->assertResponseOk();

        $response = (array)json_decode($this->response->content());

        $this->assertArrayHasKey('name', $response);
        $this->assertArrayHasKey('email', $response);
        $this->assertArrayHasKey('id', $response);

        $this->notSeeInDatabase('users', [
            'name' => $user->name,
            'email' => $user->email,
            'id' => $user->id
        ]);

    }

    public function testAllUsers() {
        $this->get('/api/users');
        $this->assertResponseOk();
        $this->seeJsonStructure([
            '*' => [
                'id',
                'name',
                'email'
            ]
        ]);
    }

    public function testDeleteUser() {
        $user = \App\Models\User::first();

        $this->delete('/api/user/' . $user->id);
        $this->assertResponseOk();
        $this->assertEquals('Removido com sucesso', $this->response->content());
    }
}
