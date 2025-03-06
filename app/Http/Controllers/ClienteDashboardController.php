<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Servico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ClienteDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:cliente');
    }

    public function index()
    {
        $cliente = Auth::guard('cliente')->user();
        $agendamentos = Agendamento::with(['servicos', 'servicos.status'])
        ->where('cliente_id', $cliente->id)
        ->get();
        $servicos = Servico::all();

        return view('clientes.dashboard', [
            'cliente' => $cliente,
            'agendamentos' => $agendamentos,
            'servicos' => $servicos,
        ]);
    }

    public function novoAgendamento(Request $request)
    {
        $request->validate([
            'dataHora' => 'required|date',
            'servicos' => 'required|array|min:1',
        ]);
    
        $clienteId = Auth::guard('cliente')->id();
        $dataHoraSolicitada = Carbon::parse($request->dataHora);
        $inicioSemana = $dataHoraSolicitada->copy()->startOfWeek(Carbon::MONDAY);
        $fimSemana = $dataHoraSolicitada->copy()->endOfWeek(Carbon::SUNDAY);
    
        $agendamentoExistente = Agendamento::where('cliente_id', $clienteId)
        ->whereBetween('dataHora', [$inicioSemana, $fimSemana])
        ->first();
    
        if ($agendamentoExistente) {
            return redirect()->back()->with([
                'warning' => 'Você já tem um agendamento nesta semana. Deseja agendar para a mesma data e hora?',
                'dataHoraSugerida' => $agendamentoExistente->dataHora,
                'dataHoraSolicitada' => $dataHoraSolicitada,
                'agendamentoExistenteId' => $agendamentoExistente->id,
                'servicosSolicitados' => $request->servicos
            ]);
        }

        $funcionario = \App\Models\Funcionario::inRandomOrder()->first();
    
        if (!$funcionario) {
            return redirect()->back()->with('error', 'Não há funcionários disponíveis para agendamento.');
        }
    
        $agendamento = new Agendamento();
        $agendamento->cliente_id = $clienteId;
        $agendamento->funcionario_id = $funcionario->id;
        $agendamento->dataHora = $request->dataHora;
        $agendamento->duracao = 60;
        $agendamento->save();
    
        $agendamento->servicos()->attach($request->servicos);
    
        return redirect()->route('cliente.dashboard')->with('success', 'Agendamento criado com sucesso!');
    }    

    public function editarAgendamento($id)
    {
        $agendamento = Agendamento::findOrFail($id);
        $servicos = Servico::all();

        return view('clientes.agendamento.editar', [
            'agendamento' => $agendamento,
            'servicos' => $servicos,
        ]);
    }

    public function atualizarAgendamento(Request $request, $id)
    {
        $request->validate([
            'dataHora' => 'required|date',
            'servicos' => 'required|array|min:1',
        ]);

        $agendamento = Agendamento::findOrFail($id);
        $agendamento->dataHora = $request->dataHora;
        $agendamento->servicos()->sync($request->servicos);
        $agendamento->save();

        return redirect()->route('cliente.dashboard')->with('success', 'Agendamento atualizado com sucesso!');
    }

    public function detalhesAgendamento($id)
    {
        $agendamento = Agendamento::findOrFail($id);

        return view('clientes.agendamento.detalhes', [
            'agendamento' => $agendamento,
        ]);
    }

    public function confirmarAgendamento($id)
    {
        $agendamento = Agendamento::findOrFail($id);
    
        if ($agendamento->cliente_id !== Auth::guard('cliente')->id()) {
            return redirect()->route('cliente.dashboard')->with('error', 'Você não tem permissão para confirmar este agendamento.');
        }
    
        $agendamento->confirmado = true;
        $agendamento->save();
    
        return redirect()->route('cliente.dashboard')->with('success', 'Agendamento confirmado com sucesso!');
    }

    public function aceitarSugestao(Request $request, $agendamentoExistenteId)
    {
        $agendamentoExistente = Agendamento::findOrFail($agendamentoExistenteId);
        $servicos = $request->servicos;

        $novoAgendamento = new Agendamento();
        $novoAgendamento->cliente_id = Auth::guard('cliente')->id();
        $novoAgendamento->funcionario_id = $agendamentoExistente->funcionario_id;
        $novoAgendamento->dataHora = $agendamentoExistente->dataHora;
        $novoAgendamento->duracao = 60;
        $novoAgendamento->save();

        $novoAgendamento->servicos()->attach($servicos);

        return redirect()->route('cliente.dashboard')->with('success', 'Novo agendamento criado com sucesso para a data sugerida!');
    }

    public function persistirNovoAgendamento(Request $request)
    {
        $request->validate([
            'dataHora' => 'required|date',
            'servicos' => 'required|array|min:1',
        ]);

        $clienteId = Auth::guard('cliente')->id();
        if (!$clienteId) {
            return redirect()->route('login')->with('error', 'Por favor, faça login para agendar.');
        }

        $dataHora = $request->dataHora ?? session('dataHoraSolicitada');
        if (!$dataHora) {
            return redirect()->back()->with('error', 'Data e hora não fornecidas.');
        }

        $funcionario = \App\Models\Funcionario::inRandomOrder()->first();
        if (!$funcionario) {
            return redirect()->back()->with('error', 'Não há funcionários disponíveis para agendamento.');
        }

        $agendamento = new Agendamento();
        $agendamento->cliente_id = $clienteId;
        $agendamento->funcionario_id = $funcionario->id;
        $agendamento->dataHora = $dataHora;
        $agendamento->duracao = 60;
        $agendamento->save();


        $servicos = $request->servicos ?? session('servicosSolicitados', []);
        $agendamento->servicos()->attach($servicos);


        session()->forget(['dataHoraSolicitada', 'servicosSolicitados']);

        return redirect()->route('cliente.dashboard')->with('success', 'Novo agendamento criado com sucesso!');
    }


}
