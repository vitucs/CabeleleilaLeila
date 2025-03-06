<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Funcionario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

class FuncionarioAuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_login_form_redirects_if_already_authenticated()
    {
        $funcionario = Funcionario::factory()->create();
        Auth::guard('funcionario')->login($funcionario);

        $response = $this->get(route('funcionario.login'));

        $response->assertRedirect(route('funcionario.dashboard'));
    }

    public function test_show_login_form_displays_login_view()
    {
        $response = $this->get(route('funcionario.login'));

        $response->assertViewIs('auth.funcionarios.login');
    }

    public function test_login_with_valid_credentials()
    {
        $funcionario = Funcionario::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post(route('funcionario.login'), [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('funcionario.dashboard'));
        $this->assertAuthenticatedAs($funcionario, 'funcionario');
    }

    public function test_login_with_invalid_credentials()
    {
        $response = $this->post(route('funcionario.login'), [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest('funcionario');
    }

    public function test_logout()
    {
        $funcionario = Funcionario::factory()->create();
        Auth::guard('funcionario')->login($funcionario);

        $response = $this->post(route('funcionario.logout'));

        $response->assertRedirect('/');
        $this->assertGuest('funcionario');
    }
}
