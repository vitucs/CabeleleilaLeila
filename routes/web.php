<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\ClienteAuthController;
use App\Http\Controllers\Auth\FuncionarioAuthController;
use App\Http\Controllers\Auth\ClienteRegisterController;
use App\Http\Controllers\Auth\FuncionarioRegisterController;
use App\Http\Controllers\ClienteDashboardController;
use App\Http\Controllers\FuncionarioDashboardController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/login', [HomeController::class, 'index']);

Auth::routes();

Route::middleware('throttle:6,1')->group(function () {
    Route::post('cliente/login', [ClienteAuthController::class, 'login']);
    Route::post('funcionario/login', [FuncionarioAuthController::class, 'login']);
    Route::post('cliente/register', [ClienteRegisterController::class, 'register']);
    Route::post('funcionario/register', [FuncionarioRegisterController::class, 'register']);
});

Route::prefix('cliente')->name('cliente.')->group(function () {
    Route::get('login', [ClienteAuthController::class, 'showLoginForm'])->name('login');
    Route::post('logout', [ClienteAuthController::class, 'logout'])->name('logout');
    Route::get('register', [ClienteRegisterController::class, 'showRegistrationForm'])->name('register');
    
    Route::middleware('auth:cliente')->group(function () {
        Route::get('dashboard', [ClienteDashboardController::class, 'index'])->name('dashboard');
        
        Route::prefix('agendamento')->name('agendamento.')->group(function () {
            Route::post('novo', [ClienteDashboardController::class, 'novoAgendamento'])->name('novo');
            Route::get('{id}/editar', [ClienteDashboardController::class, 'editarAgendamento'])->name('editar');
            Route::put('{id}/atualizar', [ClienteDashboardController::class, 'atualizarAgendamento'])->name('atualizar');
            Route::get('{id}/detalhes', [ClienteDashboardController::class, 'detalhesAgendamento'])->name('detalhes');
            Route::post('{id}/confirmar', [ClienteDashboardController::class, 'confirmarAgendamento'])->name('confirmar');
            Route::post('aceitar-sugestao/{id}', [ClienteDashboardController::class, 'aceitarSugestao'])->name('aceitar-sugestao');
            Route::post('persistir-novo', [ClienteDashboardController::class, 'persistirNovoAgendamento'])->name('persistir-novo');
        });
    });
});

Route::prefix('funcionario')->name('funcionario.')->group(function () {
    Route::get('login', [FuncionarioAuthController::class, 'showLoginForm'])->name('login');
    Route::get('register', [FuncionarioRegisterController::class, 'showRegistrationForm'])->name('register');
    
    Route::middleware('auth:funcionario')->group(function () {
        Route::post('logout', [FuncionarioAuthController::class, 'logout'])->name('logout');
        Route::get('dashboard', [FuncionarioDashboardController::class, 'index'])->name('dashboard');
        
        Route::prefix('agendamento')->name('agendamento.')->group(function () {
            Route::post('{id}/confirmar', [FuncionarioDashboardController::class, 'confirmarAgendamento'])->name('confirmar');
            Route::get('{id}/editar', [FuncionarioDashboardController::class, 'editarAgendamento'])->name('editar');
            Route::put('{id}', [FuncionarioDashboardController::class, 'atualizarAgendamento'])->name('atualizar');
            Route::get('{id}/gerenciar-servicos', [FuncionarioDashboardController::class, 'gerenciarServicos'])->name('gerenciar-servicos');
            Route::put('{agendamentoId}/servico/{servicoId}', [FuncionarioDashboardController::class, 'atualizarStatusServico'])->name('atualizar-status-servico');
            Route::delete('{id}', [FuncionarioDashboardController::class, 'excluirAgendamento'])->name('excluir');
        });
    });
});
