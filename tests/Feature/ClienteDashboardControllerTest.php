<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Models\Agendamento;
use App\Models\Servico;
use App\Models\Cliente;
use App\Models\Funcionario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class ClienteDashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $cliente;

    public function setUp(): void
    {
        parent::setUp();

        $this->cliente = Cliente::factory()->create();
        Auth::guard('cliente')->login($this->cliente);
    }

    public function testIndex()
    {
        Agendamento::factory()->count(3)->create(['cliente_id' => $this->cliente->id]);
        Servico::factory()->count(5)->create();

        $response = $this->get(route('cliente.dashboard'));

        $response->assertViewIs('clientes.dashboard');
        $response->assertViewHasAll(['cliente', 'agendamentos', 'servicos']);
    }

    public function testNovoAgendamento()
    {
        $servicos = Servico::factory()->count(3)->create();
        Funcionario::factory()->create();

        $dataHora = Carbon::now()->addDays(2)->format('Y-m-d H:i:s');
        $payload = [
            'dataHora' => $dataHora,
            'servicos' => $servicos->pluck('id')->toArray(),
        ];

        $response = $this->post(route('cliente.agendamento.novo'), $payload);

        $this->assertDatabaseHas('agendamentos', [
            'cliente_id' => $this->cliente->id,
            'dataHora' => $dataHora,
        ]);

        $response->assertRedirect(route('cliente.dashboard'));
    }

    public function testEditarAgendamento()
    {
        $agendamento = Agendamento::factory()->create(['cliente_id' => $this->cliente->id]);
        Servico::factory()->count(3)->create();

        $response = $this->get(route('cliente.agendamento.editar', ['id' => $agendamento->id]));

        $response->assertViewIs('clientes.agendamento.editar');
        $response->assertViewHasAll(['agendamento', 'servicos']);
    }

    public function testAtualizarAgendamento()
    {
        $agendamento = Agendamento::factory()->create(['cliente_id' => $this->cliente->id]);
        $servicos = Servico::factory()->count(2)->create();

        $dataHoraNova = Carbon::now()->addDays(5)->format('Y-m-d H:i:s');
        $payload = [
            'dataHora' => $dataHoraNova,
            'servicos' => $servicos->pluck('id')->toArray(),
        ];

        $response = $this->put(route('cliente.agendamento.atualizar', ['id' => $agendamento->id]), $payload);

        $this->assertDatabaseHas('agendamentos', [
            'id' => $agendamento->id,
            'dataHora' => $dataHoraNova,
        ]);

        $response->assertRedirect(route('cliente.dashboard'));
    }

    public function testDetalhesAgendamento()
    {
        $agendamento = Agendamento::factory()->create(['cliente_id' => $this->cliente->id]);

        $response = $this->get(route('cliente.agendamento.detalhes', ['id' => $agendamento->id]));

        $response->assertViewIs('clientes.agendamento.detalhes');
        $response->assertViewHas('agendamento');
    }

    public function testConfirmarAgendamento()
    {
        $agendamento = Agendamento::factory()->create([
            'cliente_id' => $this->cliente->id,
            'confirmado' => false,
        ]);

        $response = $this->post(route('cliente.agendamento.confirmar', ['id' => $agendamento->id]));

        $this->assertDatabaseHas('agendamentos', [
            'id' => $agendamento->id,
            'confirmado' => true,
        ]);

        $response->assertRedirect(route('cliente.dashboard'));
    }

    public function testPersistirNovoAgendamento()
    {
        $servicos = Servico::factory()->count(2)->create();
        
        Funcionario::factory()->create();

        $dataHora = Carbon::now()->addDays(3)->format('Y-m-d H:i:s');
        $payload = [
            'dataHora' => $dataHora,
            'servicos' => $servicos->pluck('id')->toArray(),
        ];

        Session::put('dataHoraSolicitada', $dataHora);
        Session::put('servicosSolicitados', $servicos->pluck('id')->toArray());

        $response = $this->post(route('cliente.agendamento.persistir-novo'), $payload);

        $this->assertDatabaseHas('agendamentos', [
            'cliente_id' => $this->cliente->id,
            'dataHora' => $dataHora,
        ]);

        $novoAgendamento = Agendamento::latest()->first();
        $this->assertEquals($servicos->pluck('id')->toArray(), $novoAgendamento->servicos->pluck('id')->toArray());

        $this->assertNull(Session::get('dataHoraSolicitada'));
        $this->assertNull(Session::get('servicosSolicitados'));

        $response->assertRedirect(route('cliente.dashboard'));
    }
}
