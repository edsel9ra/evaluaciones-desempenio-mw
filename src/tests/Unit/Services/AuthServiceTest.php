<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    protected \App\Services\AuthService $authService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authService = new \App\Services\AuthService();
    }

    public function test_login_returns_success_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'username' => 'testuser',
            'password' => Hash::make('password123')
        ]);

        $result = $this->authService->login('testuser', 'password123');

        $this->assertTrue($result['success']);
        $this->assertEquals($user->id, $result['user']->id);
    }

    public function test_login_returns_error_with_invalid_username(): void
    {
        $result = $this->authService->login('nonexistent', 'password');

        $this->assertFalse($result['success']);
        $this->assertEquals('Invalid credentials', $result['message']);
    }

    public function test_login_returns_error_with_invalid_password(): void
    {
        User::factory()->create([
            'username' => 'testuser',
            'password' => Hash::make('correctpassword')
        ]);

        $result = $this->authService->login('testuser', 'wrongpassword');

        $this->assertFalse($result['success']);
        $this->assertEquals('Invalid credentials', $result['message']);
    }

    public function test_login_returns_error_with_empty_credentials(): void
    {
        $result = $this->authService->login('', '');

        $this->assertFalse($result['success']);
        $this->assertEquals('Invalid credentials', $result['message']);
    }

    public function test_logout_calls_auth_logout(): void
    {
        Auth::shouldReceive('logout')->once()->andReturn(true);

        $this->authService->logout();
    }

    public function test_get_current_user_returns_user_when_logged_in(): void
    {
        $user = User::factory()->create();
        Auth::login($user);

        $result = $this->authService->getCurrentUser();

        $this->assertEquals($user->id, $result->id);
    }

    public function test_get_current_user_returns_null_when_not_logged_in(): void
    {
        Auth::shouldReceive('user')->andReturn(null)->once();

        $result = $this->authService->getCurrentUser();

        $this->assertNull($result);
    }
}