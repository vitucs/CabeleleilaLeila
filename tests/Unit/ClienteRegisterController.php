<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Cliente;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClienteRegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_registration_form()
    {
        $response = $this->get(route('cliente.register'));

        $response->assertViewIs('auth.clientes.register');
    }

    public function test_register_with_valid_data()
    {
        $response = $this->post(route('cliente.register'), [
            'nome' => 'John',
            'sobrenome' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('cliente.dashboard'));
        $this->assertAuthenticated('cliente');
        $this->assertDatabaseHas('clientes', [
            'nome' => 'John',
            'sobrenome' => 'Doe',
            'email' => 'john@example.com',
        ]);
    }

    public function test_register_with_invalid_data()
    {
        $response = $this->post(route('cliente.register'), [
            'nome' => '',
            'sobrenome' => '',
            'email' => 'not-an-email',
            'password' => 'short',
            'password_confirmation' => 'different',
        ]);

        $response->assertSessionHasErrors(['nome', 'sobrenome', 'email', 'password']);
        $this->assertGuest('cliente');
    }

    public function test_register_with_existing_email()
    {
        Cliente::factory()->create(['email' => 'existing@example.com']);

        $response = $this->post(route('cliente.register'), [
            'nome' => 'John',
            'sobrenome' => 'Doe',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest('cliente');
    }
}
