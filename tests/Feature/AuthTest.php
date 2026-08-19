<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_customer_can_view_login_page(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Welcome Back!');
    }

    public function test_customer_can_login_with_valid_credentials(): void
    {
        $response = $this->post('/login', [
            'email' => 'user@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertTrue(Auth::check());
        $this->assertEquals('user@example.com', Auth::user()->email);
    }

    public function test_customer_can_register_new_account(): void
    {
        $response = $this->post('/register', [
            'name' => 'New Customer',
            'email' => 'newcustomer@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertTrue(Auth::check());

        $user = DB::table('users')->where('email', 'newcustomer@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('user', $user->role);
    }

    public function test_customer_can_logout(): void
    {
        $user = DB::table('users')->where('role', 'user')->first();
        $this->actingAs(User::find($user->id));

        $response = $this->post('/logout');

        $response->assertRedirect(route('home'));
        $this->assertFalse(Auth::check());
    }

    public function test_admin_can_login_to_admin_panel(): void
    {
        $response = $this->post('/admin/login', [
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertTrue(Auth::check());
        $this->assertEquals('admin', Auth::user()->role);
    }

    public function test_non_admin_cannot_access_admin_panel(): void
    {
        $user = DB::table('users')->where('role', 'user')->first();
        $this->actingAs(User::find($user->id));

        $response = $this->get('/admin');

        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_logout(): void
    {
        $admin = DB::table('users')->where('role', 'admin')->first();
        $this->actingAs(User::find($admin->id));

        $response = $this->post('/admin/logout');

        $response->assertRedirect(route('admin.login'));
        $this->assertFalse(Auth::check());
    }
}
