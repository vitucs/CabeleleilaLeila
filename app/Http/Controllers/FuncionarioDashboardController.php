<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agendamento;
use App\Models\Servico;
use App\Models\Status;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class FuncionarioDashboardController extends Controller
{
    public function index()
    {
        $funcionario = Auth::guard('funcionario')->user();
        $agendamentos = Agendamento::with(['cliente', 'servicos'])->orderBy('dataHora')->get();
        $totalAgendamentos = $agendamentos->count();
        $receitaTotal = $agendamentos->sum(function ($agendamento) {
            return $agendamento->servicos->sum('valor');
        });

        return view('funcionarios.dashboard', compact('agendamentos', 'totalAgendamentos', 'receitaTotal', 'funcionario'));
    }

    public function confirmarAgendamento($id)
    {
        $agendamento = Agendamento::findOrFail($id);
        $agendamento->confirmado = true;
        $agendamento->save();

        return redirect()->route('funcionario.dashboard')->with('success', 'Agendamento confirmado com sucesso!');
    }

    public function editarAgendamento($id)
    {
        $agendamento = Agendamento::with('servicos')->findOrFail($id);
        $servicos = Servico::all();

        return view('funcionarios.agendamento.editar-agendamento', compact('agendamento', 'servicos'));
    }

    public function atualizarAgendamento(Request $request, $id)
    {
        $request->validate([
            'dataHora' => 'required|date',
            'servicos' => 'required|array|min:1',
        ]);

        $agendamento = Agendamento::findOrFail($id);
        $agendamento->dataHora = $request->dataHora;
        $agendamento->save();

        $agendamento->servicos()->sync($request->servicos);

        return redirect()->route('funcionario.dashboard')->with('success', 'Agendamento atualizado com sucesso!');
    }

    public function gerenciarServicos($id)
    {
        $agendamento = Agendamento::with('servicos')->findOrFail($id);
        $statuses = Status::all();
        return view('funcionarios.agendamento.gerenciar-servicos', compact('agendamento', 'statuses'));
    }

    public function atualizarStatusServico(Request $request, $agendamentoId, $servicoId)
    {
        $request->validate([
            'status_id' => 'required|exists:status,id',
        ]);

        $agendamento = Agendamento::findOrFail($agendamentoId);

        $agendamento->servicos()->updateExistingPivot($servicoId, [
            'status_id' => $request->status_id
        ]);

        return redirect()->back()->with('success', 'Status do serviço atualizado com sucesso!');
    }

    public function excluirAgendamento($id)
    {
        $agendamento = Agendamento::findOrFail($id);
        $agendamento->delete();

        return redirect()->route('funcionario.dashboard')->with('success', 'Agendamento excluído com sucesso!');
    }
}
