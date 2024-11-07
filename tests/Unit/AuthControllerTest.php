<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Hash;


class AuthControllerTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    use RefreshDatabase, WithoutMiddleware;

    public function test_user_can_login_with_correct_credentials()
    {
        $user = User::factory()->create([
            'email' => 'jorge@gmail.com',
            'password' => Hash::make('1234')
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'jorge@gmail.com',
            'password' => '1234'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'expires_in'
            ]);
    }
}
