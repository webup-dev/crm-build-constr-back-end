<?php

/**
 * Test of testing system.
 * Create user.
 * Test get me without user.
 * Test of function me.
 * Delete user.
 */

namespace Tests\Unit;

use App\Models\User;
use App\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    /**
     * Create a new user
     *
     * @return mixed
     */
    public function testCreateUser()
    {
        $user = factory(User::class)->create();
        $this->assertGreaterThan(0, $user->id);

        return $user;
    }

    /**
     * Attempt to get function me without user's token that is required.
     */
    public function testMeWithoutToken()
    {
        $response = $this->json('GET', '/api/auth/me');
        $response->assertStatus(401);
//        $response->assertJson({'error'=> ["message"=> "Token not provided", "status"=> 401}'});
    }

    /**
     * Test of function me.
     */
    public function testMe()
    {
        $response = $this->post('api/auth/login', [
            'email' => 'test@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token = $responseJSON['token'];

        $this->get('api/auth/me?token=' . $token, [], [])->assertJson([
            'name' => 'Test',
            'email' => 'test@email.com'
        ])->isOk();
    }

    /**
     * Deleting of the used User.
     */
    public function testDeleteUsedUser()
    {
        $response = $this->post('api/auth/login', [
            'email' => 'test@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $responseJSON = json_decode($response->getContent(), true);
        $token = $responseJSON['token'];

        $me=$this->get('api/auth/me?token=' . $token, [], []);
        $array = $me->getOriginalContent();
        $id=$array['id'];

        $this->delete('api/users/' . $id . '?token=' . $token, [], [])->assertStatus(200);
    }

}
