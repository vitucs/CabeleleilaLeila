@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Editar Agendamento</h1>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('cliente.agendamento.atualizar', $agendamento->id) }}" method="POST">
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

                <button type="submit" class="btn btn-primary me-2">Atualizar Agendamento</button>
                <a href="{{ route('cliente.dashboard') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function toggleSelection(option) {
            option.selected = !option.selected;
        }
    });
</script>
@endsection
