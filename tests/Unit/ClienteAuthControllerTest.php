<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Cliente;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

class ClienteAuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_login_form_redirects_if_already_authenticated()
    {
        $cliente = Cliente::factory()->create();
        Auth::guard('cliente')->login($cliente);

        $response = $this->get(route('cliente.login'));

        $response->assertRedirect(route('cliente.dashboard'));
    }

    public function test_show_login_form_displays_login_view()
    {
        $response = $this->get(route('cliente.login'));

        $response->assertViewIs('auth.clientes.login');
    }

    public function test_login_with_valid_credentials()
    {
        $cliente = Cliente::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post(route('cliente.login'), [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('cliente.dashboard'));
        $this->assertAuthenticatedAs($cliente, 'cliente');
    }

    public function test_login_with_invalid_credentials()
    {
        $response = $this->post(route('cliente.login'), [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest('cliente');
    }

    public function test_logout()
    {
        $cliente = Cliente::factory()->create();
        Auth::guard('cliente')->login($cliente);

        $response = $this->post(route('cliente.logout'));

        $response->assertRedirect('/');
        $this->assertGuest('cliente');
    }
}
