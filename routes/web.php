<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\ClienteAuthController;
use App\Http\Controllers\Auth\FuncionarioAuthController;
use App\Http\Controllers\Auth\ClienteRegisterController;
use App\Http\Controllers\Auth\FuncionarioRegisterController;
use App\Http\Controllers\ClienteDashboardController;
use App\Http\Controllers\FuncionarioDashboardController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('/login', [HomeController::class, 'index']);
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::middleware('throttle:6,1')->group(function () {
    Route::post('cliente/login', [ClienteAuthController::class, 'login']);
    Route::post('funcionario/login', [FuncionarioAuthController::class, 'login']);
    Route::post('cliente/register', [ClienteRegisterController::class, 'register']);
    Route::post('funcionario/register', [FuncionarioRegisterController::class, 'register']);
});

Route::get('cliente/login', [ClienteAuthController::class, 'showLoginForm'])->name('cliente.login');
Route::post('cliente/logout', [ClienteAuthController::class, 'logout'])->name('cliente.logout');
Route::get('cliente/register', [ClienteRegisterController::class, 'showRegistrationForm'])->name('cliente.register');
Route::get('cliente/dashboard', [ClienteDashboardController::class, 'index'])->name('cliente.dashboard');
Route::post('/cliente/agendamento/novo', [ClienteDashboardController::class, 'novoAgendamento'])->name('cliente.agendamento.novo');
Route::get('/cliente/agendamento/{id}/editar', [ClienteDashboardController::class, 'editarAgendamento'])->name('cliente.agendamento.editar');
Route::put('/cliente/agendamento/{id}/atualizar', [ClienteDashboardController::class, 'atualizarAgendamento'])->name('cliente.agendamento.atualizar');
Route::get('/cliente/agendamento/{id}/detalhes', [ClienteDashboardController::class, 'detalhesAgendamento'])->name('cliente.agendamento.detalhes');
Route::post('/cliente/agendamento/{id}/confirmar', [ClienteDashboardController::class, 'confirmarAgendamento'])->name('cliente.agendamento.confirmar');
Route::post('/cliente/agendamento/aceitar-sugestao/{id}', [ClienteDashboardController::class, 'aceitarSugestao'])->name('cliente.agendamento.aceitar-sugestao');
Route::post('/cliente/agendamento/persistir-novo', [ClienteDashboardController::class, 'persistirNovoAgendamento'])->name('cliente.agendamento.persistir-novo');

Route::get('funcionario/login', [FuncionarioAuthController::class, 'showLoginForm'])->name('funcionario.login');
Route::get('funcionario/register', [FuncionarioRegisterController::class, 'showRegistrationForm'])->name('funcionario.register');

Route::middleware(['auth:funcionario'])->group(function () {
    Route::post('funcionario/logout', [FuncionarioAuthController::class, 'logout'])->name('funcionario.logout');
    Route::get('/funcionario/dashboard', [FuncionarioDashboardController::class, 'index'])->name('funcionario.dashboard');
    Route::post('/funcionario/agendamento/{id}/confirmar', [FuncionarioDashboardController::class, 'confirmarAgendamento'])->name('funcionario.agendamento.confirmar');
    Route::get('/funcionario/agendamento/{id}/editar', [FuncionarioDashboardController::class, 'editarAgendamento'])->name('funcionario.agendamento.editar');
    Route::put('/funcionario/agendamento/{id}', [FuncionarioDashboardController::class, 'atualizarAgendamento'])->name('funcionario.agendamento.atualizar');
    Route::get('/funcionario/agendamento/{id}/gerenciar-servicos', [FuncionarioDashboardController::class, 'gerenciarServicos'])->name('funcionario.agendamento.gerenciar-servicos');
    Route::put('/funcionario/agendamento/{agendamentoId}/servico/{servicoId}', [FuncionarioDashboardController::class, 'atualizarStatusServico'])->name('funcionario.agendamento.atualizar-status-servico');
    Route::delete('/funcionario/agendamento/{id}', [FuncionarioDashboardController::class, 'excluirAgendamento'])->name('funcionario.agendamento.excluir');
});
    