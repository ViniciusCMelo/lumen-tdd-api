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
            'password' => '123'
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

    public function testUpdateUser() {
        $user = \App\Models\User::first();

        $data = [
            'name' => 'Nome 01' . date('Ymdis') . rand(1, 100),
            'email' => 'email3' . date('Ymdis') . rand(1, 100) . '@exemplo.com',
            'password' => '123'
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
}
