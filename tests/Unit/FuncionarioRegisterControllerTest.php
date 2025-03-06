<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Funcionario;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FuncionarioRegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_registration_form()
    {
        $response = $this->get(route('funcionario.register'));

        $response->assertViewIs('auth.funcionarios.register');
    }

    public function test_register_with_valid_data()
    {
        $response = $this->post(route('funcionario.register'), [
            'nome' => 'John',
            'sobrenome' => 'Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('funcionario.dashboard'));
        $this->assertAuthenticated('funcionario');
        $this->assertDatabaseHas('funcionarios', [
            'nome' => 'John',
            'sobrenome' => 'Doe',
            'email' => 'john@example.com',
        ]);
    }

    public function test_register_with_invalid_data()
    {
        $response = $this->post(route('funcionario.register'), [
            'nome' => '',
            'sobrenome' => '',
            'email' => 'not-an-email',
            'password' => 'short',
            'password_confirmation' => 'different',
        ]);

        $response->assertSessionHasErrors(['nome', 'sobrenome', 'email', 'password']);
        $this->assertGuest('funcionario');
    }

    public function test_register_with_existing_email()
    {
        Funcionario::factory()->create(['email' => 'existing@example.com']);

        $response = $this->post(route('funcionario.register'), [
            'nome' => 'John',
            'sobrenome' => 'Doe',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest('funcionario');
    }
}
