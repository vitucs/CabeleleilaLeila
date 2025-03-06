@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Detalhes do Agendamento</h1>

    <div class="card">
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Data e Hora:</dt>
                <dd class="col-sm-9">{{ \Carbon\Carbon::parse($agendamento->dataHora)->format('d/m/Y H:i') }}</dd>

                <dt class="col-sm-3">Serviços:</dt>
                <dd class="col-sm-9">
                    <ul>
                        @foreach($agendamento->servicos as $servico)
                            <li>{{ $servico->nome }} - R$ {{ number_format($servico->valor, 2, ',', '.') }}</li>
                        @endforeach
                    </ul>
                </dd>

                <dt class="col-sm-3">Valor Total:</dt>
                <dd class="col-sm-9">R$ {{ number_format($agendamento->servicos->sum('valor'), 2, ',', '.') }}</dd>

                <dt class="col-sm-3">Status:</dt>
                <dd class="col-sm-9">
                    @if($agendamento->confirmado)
                        <span class="badge bg-success">Confirmado</span>
                    @else
                        <span class="badge bg-warning text-dark">Pendente</span>
                    @endif
                </dd>
            </dl>

            @php
                $podeAlterar = \Carbon\Carbon::parse($agendamento->dataHora)->diffInDays(now()) > 2;
            @endphp

            @if($podeAlterar)
                <a href="{{ route('cliente.agendamento.editar', $agendamento->id) }}" class="btn btn-warning me-2">
                    Alterar Agendamento
                </a>
            @else
                <button class="btn btn-secondary me-2" disabled>Alteração não permitida (menos de 2 dias)</button>
            @endif

            <a href="{{ route('cliente.dashboard') }}" class="btn btn-primary">Voltar para Dashboard</a>
        </div>
    </div>
</div>
@endsection
