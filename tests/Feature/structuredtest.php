<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Auth;

class structuredtest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    // public function test_example()
    // {
    //     $user = \User::factory()->create();
    //     //$this->actingAs($user,'auth');

    //     $response =  $this->actingAs($user,Auth)->withHeaders(['Accept' => 'application/json'])->get('/new-structured-question');

    //     $response->assertStatus(200);

    //     // $response = $this->post('/new-structured-question', ['vName' => 'Sally','description' => 'test test' ,'status' => '1']);
 
    //     // $response->assertStatus(201);
    // }

    use RefreshDatabase, WithFaker;

    public function test_user_can_login_with_correct_credentials()
    {
        $user = User::factory()->create([
            'email' => 'ayodhya1@tekgeeks.net',
            'password' => bcrypt('1234567'),
        ]);

        $response = $this->post('/login', [
            'email' => 'ayodhya1@tekgeeks.net',
            'password' => '1234567',
        ]);

        $response->assertRedirect('/dashboard'); // Assuming you redirect to /dashboard on successful login
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_incorrect_credentials()
    {
        $user = User::factory()->create([
            'email' => 'ayodhya2@tekgeeks.net',
            'password' => bcrypt('1234567'),
        ]);

        $response = $this->post('/login', [
            'email' => 'ayodhya2@tekgeeks.net',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }
}
