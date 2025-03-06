@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-5">Bem vindo(a), {{ $cliente->nome }}  {{ $cliente->sobrenome }}</h1>

        @if(session('warning'))
            <div class="alert alert-warning">
                {{ session('warning') }}
                <p>Você já possui um agendamento para {{ Carbon\Carbon::parse(session('dataHoraSugerida'))->format('d/m/Y H:i') }}.</p>
                <p>Deseja adicionar os novos serviços a este agendamento?</p>
                <form action="{{ route('cliente.agendamento.aceitar-sugestao', session('agendamentoExistenteId')) }}" method="POST">
                    @csrf
                    @foreach(session('servicosSolicitados') as $servicoId)
                        <input type="hidden" name="servicos[]" value="{{ $servicoId }}">
                    @endforeach
                    <button type="submit" class="btn btn-primary">Sim, adicionar ao agendamento existente</button>
                </form>
                <br/>
                <form action="{{ route('cliente.agendamento.persistir-novo') }}" method="POST" id="formNovoAgendamento">
                    @csrf
                    <input type="hidden" name="dataHora" value="{{ session('dataHoraSolicitada') }}">
                    @foreach(session('servicosSolicitados') as $servicoId)
                        <input type="hidden" name="servicos[]" value="{{ $servicoId }}">
                    @endforeach
                    <button type="submit" class="btn btn-secondary">Não, fazer um novo agendamento</button>
                </form>
            </div>
        @endif
        <div class="card mb-5" @if(session('warning')) style="display: none;" @endif id="cardAgendamento">
            <div class="card-header">
                Agendar Novo Serviço
            </div>
            <div class="card-body">
                <form id="formAgendamento" action="{{ route('cliente.agendamento.novo') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="dataHora" class="form-label">Data e Hora</label>
                        <input type="datetime-local" class="form-control" id="dataHora" name="dataHora" 
                            value="{{ session('dataHoraSugerida') ?? old('dataHora') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="servicos" class="form-label">Serviços</label>
                        <select multiple class="form-control" id="servicos" name="servicos[]" required>
                            @foreach($servicos as $servico)
                                <option value="{{ $servico->id }}" onclick="toggleSelection(this)">
                                    {{ $servico->nome }} (R$ {{ number_format($servico->valor, 2, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Agendar</button>
                </form>
            </div>
        </div>

        <h2>Meus Agendamentos</h2>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Data e Hora</th>
                    <th>Serviços</th>
                    <th>Ações</th>
                </tr>
                </thead>
                <tbody>
                @foreach($agendamentos->sortBy('dataHora') as $agendamento)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($agendamento->dataHora)->format('d/m/Y H:i') }}</td>
                        <td>
                            <ul>
                                @foreach($agendamento->servicos as $servico)
                                    <li>
                                        {{ $servico->nome }} -
                                        Status do Serviço: 
                                        @if($servico->pivot->status_id)
                                            
                                                @switch($servico->pivot->status_id)
                                                    @case('1')
                                                        <span class="badge bg-{{ $servico->pivot->status->cor ?? 'secondary' }}">
                                                        Pendente
                                                        </span>
                                                        @break
                                                    @case('2')
                                                        <span class="badge bg-{{ $servico->pivot->status->cor ?? 'primary' }}">
                                                        Em andamento
                                                        </span>
                                                        
                                                        @break
                                                    @case('3')
                                                        <span class="badge bg-{{ $servico->pivot->status->cor ?? 'success' }}">
                                                        Concluído
                                                        </span>
                                                        
                                                        @break
                                                @endswitch
                                            
                                        @else
                                            <span class="badge bg-secondary">Pendente</span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            @php
                                $dataAgendamento = \Carbon\Carbon::parse($agendamento->dataHora);
                                $diferencaDias = $dataAgendamento->diffInDays(now());
                                $podeAlterar = $diferencaDias > 2;
                            @endphp
                            @if($podeAlterar)
                                <a href="{{ route('cliente.agendamento.editar', $agendamento->id) }}" class="btn btn-sm btn-warning me-2">
                                    <i class="fas fa-edit"></i> Alterar
                                </a>
                            @else
                                <button type="button" class="btn btn-sm btn-secondary me-2" disabled>
                                    <i class="fas fa-ban"></i> Alterar (ligue)
                                </button>
                            @endif
                            <a href="{{ route('cliente.agendamento.detalhes', $agendamento->id) }}" class="btn btn-sm btn-info me-2">
                                <i class="fas fa-info-circle"></i> Detalhes
                            </a>
                            @if($agendamento->confirmado)
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="fas fa-check"></i> Confirmado
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function toggleSelection(option) {
                option.selected = !option.selected;
            }
            
            function getHojeBrasilia() {
                return new Date(new Date().toLocaleString('en-US', { timeZone: 'America/Sao_Paulo' }));
            }

            function formatarData(data) {
                return data.toLocaleString('pt-BR', {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit',
                    timeZone: 'America/Sao_Paulo'
                });
            }

            var campo = document.querySelector('#dataHora');

            function atualizarMinimo() {
                var hoje = getHojeBrasilia();
                var hojeFormatado = hoje.getFullYear() + '-' +
                                    String(hoje.getMonth() + 1).padStart(2, '0') + '-' +
                                    String(hoje.getDate()).padStart(2, '0') + 'T' +
                                    String(hoje.getHours()).padStart(2, '0') + ':' +
                                    String(hoje.getMinutes()).padStart(2, '0');
                campo.min = hojeFormatado;
            }

            atualizarMinimo();
            setInterval(atualizarMinimo, 60000);

            campo.addEventListener('change', function(e) {
                var selectedDate = new Date(e.target.value);
                var hoje = getHojeBrasilia();
                
                if (selectedDate < hoje) {
                    e.target.setCustomValidity(`A data não pode ser anterior a ${formatarData(hoje)}`);
                } else {
                    e.target.setCustomValidity('');
                }
            });

            campo.addEventListener('input', function(e) {
                e.target.checkValidity();
            });

            document.getElementById('escolherOutraData').addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector('.alert-warning').style.display = 'none';
                document.getElementById('cardAgendamento').style.display = 'block';
            });

        });
    </script>
@endsection
