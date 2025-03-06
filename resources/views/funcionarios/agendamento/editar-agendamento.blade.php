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
                    <option value="{{ $servico->id }}" 
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
@endsection
