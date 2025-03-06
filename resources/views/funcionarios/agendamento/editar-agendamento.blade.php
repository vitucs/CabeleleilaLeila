@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Editar Agendamento</h1>

    <form action="{{ route('funcionario.agendamento.atualizar', $agendamento->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="dataHora" class="form-label">Data e Hora</label>
            <input type="datetime-local" class="form-control" id="dataHora" name="dataHora" 
                   value="{{ \Carbon\Carbon::parse($agendamento->dataHora)->format('Y-m-d\TH:i') }}" required>
        </div>

        <div class="mb-3">
            <label for="servicos" class="form-label">Serviços</label>
            <select multiple class="form-control" id="servicos" name="servicos[]" required>
                @foreach($servicos as $servico)
                    <option onclick="toggleSelection(this)" value="{{ $servico->id }}" 
                        {{ $agendamento->servicos->contains($servico->id) ? 'selected' : '' }}>
                        {{ $servico->nome }} (R$ {{ number_format($servico->valor, 2, ',', '.') }})
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Atualizar Agendamento</button>
        <a href="{{ route('funcionario.dashboard') }}" class="btn btn-secondary">Cancelar</a>
    </form>
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
    });
</script>
@endsection
