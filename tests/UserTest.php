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
            'name' => 'Nome 01',
            'email' => 'email3@exemplo.com',
            'password' => '123'
        ];

        $this->post('/api/user', $data);

        $this->assertResponseOk();

        $response = (array)json_decode($this->response->content());

        $this->assertArrayHasKey('name', $response);
        $this->assertArrayHasKey('email', $response);
        $this->assertArrayHasKey('id', $response);

    }
}
