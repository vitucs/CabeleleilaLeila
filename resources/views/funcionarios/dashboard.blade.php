@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-5">Bem vindo(a), {{ $funcionario->nome }} {{ $funcionario->sobrenome }}</h1>

    <h2>Agendamentos</h2>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Data e Hora</th>
                    <th>Serviços</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($agendamentos->sortBy('dataHora') as $agendamento)
                <tr>
                    <td>{{ $agendamento->cliente->nome }}</td>
                    <td>{{ \Carbon\Carbon::parse($agendamento->dataHora)->format('d/m/Y H:i') }}</td>
                    <td>
                        <ul>
                            @foreach($agendamento->servicos as $servico)
                            <li>{{ $servico->nome }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>
                        @if($agendamento->confirmado)
                        <span class="badge bg-success">&#10004; Confirmado</span>
                        @else
                        <span class="badge bg-warning">Pendente</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex">
                            <a href="{{ route('funcionario.agendamento.editar', $agendamento->id) }}" class="btn btn-sm btn-primary me-1"><i class="fas fa-edit"></i> Editar</a>
                            <a href="{{ route('funcionario.agendamento.gerenciar-servicos', $agendamento->id) }}" class="btn btn-sm btn-info me-1"><i class="fas fa-cog"></i> Gerenciar Serviços</a>
                            <form action="{{ route('funcionario.agendamento.excluir', $agendamento->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este agendamento?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger me-2"><i class="fas fa-trash"></i> Excluir</button>
                            </form>
                            @if(!$agendamento->confirmado)
                            <form action="{{ route('funcionario.agendamento.confirmar', $agendamento->id) }}" method="POST" class="me-1">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success"><i class="fas fa-check"></i> Confirmar</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <h2 class="mt-5">Desempenho Semana Atual</h2>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total de Agendamentos</h5>
                    <p class="card-text">{{ $totalAgendamentos }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Receita Total Agendados</h5>
                    <p class="card-text">R$ {{ number_format($receitaTotal, 2, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Receita Total Recebido</h5>
                    <p class="card-text">R$ {{ number_format($totalRecebido, 2, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
