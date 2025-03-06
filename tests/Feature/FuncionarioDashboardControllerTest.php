<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Models\Agendamento;
use App\Models\Servico;
use App\Models\Status;
use App\Models\Funcionario;
use App\Models\Cliente;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FuncionarioDashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $funcionario;

    public function setUp(): void
    {
        parent::setUp();
        $this->funcionario = Funcionario::factory()->create();
        Auth::guard('funcionario')->login($this->funcionario);
    }

    public function testIndex()
    {
        Agendamento::factory()->count(5)->create();
        $response = $this->get(route('funcionario.dashboard'));
        $response->assertViewIs('funcionarios.dashboard');
        $response->assertViewHasAll(['agendamentos', 'totalAgendamentos', 'receitaTotal', 'totalRecebido', 'funcionario']);
    }

    public function testConfirmarAgendamento()
    {
        $agendamento = Agendamento::factory()->create(['confirmado' => false]);
        $response = $this->post(route('funcionario.agendamento.confirmar', $agendamento->id));
        $response->assertRedirect(route('funcionario.dashboard'));
    }

    public function testEditarAgendamento()
    {
        $agendamento = Agendamento::factory()->create();
        Servico::factory()->count(3)->create();
        $response = $this->get(route('funcionario.agendamento.editar', $agendamento->id));
        $response->assertViewIs('funcionarios.agendamento.editar-agendamento');
        $response->assertViewHasAll(['agendamento', 'servicos']);
    }

    public function testAtualizarAgendamento()
    {
        $agendamento = Agendamento::factory()->create();
        $servicos = Servico::factory()->count(2)->create();
        $novaData = Carbon::now()->addDays(5);
        $response = $this->put(route('funcionario.agendamento.atualizar', $agendamento->id), [
            'dataHora' => $novaData,
            'servicos' => $servicos->pluck('id')->toArray(),
        ]);
        $agendamento->refresh();
        $this->assertEquals($novaData->toDateTimeString(), $agendamento->dataHora->toDateTimeString());
        $response->assertRedirect(route('funcionario.dashboard'));
    }

    public function testGerenciarServicos()
    {
        $agendamento = Agendamento::factory()->create();
        Status::factory()->count(3)->create();
        $response = $this->get(route('funcionario.agendamento.gerenciar-servicos', $agendamento->id));
        $response->assertViewIs('funcionarios.agendamento.gerenciar-servicos');
        $response->assertViewHasAll(['agendamento', 'statuses']);
    }

    public function testExcluirAgendamento()
    {
        $agendamento = Agendamento::factory()->create();
        $response = $this->delete(route('funcionario.agendamento.excluir', $agendamento->id));
        $this->assertNull(Agendamento::find($agendamento->id));
        $response->assertRedirect(route('funcionario.dashboard'));
    }
}
