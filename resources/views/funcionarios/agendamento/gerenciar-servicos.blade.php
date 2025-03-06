@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Gerenciar Serviços do Agendamento</h1>

    <h2>Detalhes do Agendamento</h2>
    <p><strong>Cliente:</strong> {{ $agendamento->cliente->nome }}</p>
    <p><strong>Data e Hora:</strong> {{ \Carbon\Carbon::parse($agendamento->dataHora)->format('d/m/Y H:i') }}</p>

    <h3 class="mt-4">Serviços</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Serviço</th>
                <th>Valor</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($agendamento->servicos as $servico)
            <tr>
                <td>{{ $servico->nome }}</td>
                <td>R$ {{ number_format($servico->valor, 2, ',', '.') }}</td>
                <td>
                    @php
                        $statusNome = $statuses->find($servico->pivot->status_id)->nome ?? 'pendente';
                    @endphp
                    @switch($statusNome)
                        @case('pendente')
                            Pendente
                            @break
                        @case('em_andamento')
                            Em andamento
                            @break
                        @case('concluido')
                            Concluído
                            @break
                        @default
                            {{ ucfirst($statusNome) }}
                    @endswitch
                </td>

                <td>
                    <form action="{{ route('funcionario.agendamento.atualizar-status-servico', [$agendamento->id, $servico->id]) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <select name="status_id" class="form-select form-select-sm d-inline-block w-auto me-2">
                            @foreach($statuses as $status)
                                <option value="{{ $status->id }}" {{ $servico->pivot->status_id == $status->id ? 'selected' : '' }}>
                                    @switch($status->nome)
                                        @case('pendente')
                                            Pendente
                                            @break
                                        @case('em_andamento')
                                            Em andamento
                                            @break
                                        @case('concluido')
                                            Concluído
                                            @break
                                        @default
                                            {{ ucfirst($status->nome) }}
                                    @endswitch
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary">Atualizar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('funcionario.dashboard') }}" class="btn btn-secondary">Voltar para Dashboard</a>
</div>
@endsection
