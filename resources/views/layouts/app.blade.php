<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <!-- <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest"> -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <ul class="navbar-nav ms-auto">
                        @guest
                            
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ Auth::guard('cliente')->check() ? route('cliente.logout') : route('funcionario.logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    
    <script>
        window.onmousedown = function (e) {
            var el = e.target;
            if (el.tagName.toLowerCase() == 'option' && el.parentNode.hasAttribute('multiple')) {
                e.preventDefault();

                if (el.hasAttribute('selected')) el.removeAttribute('selected');
                else el.setAttribute('selected', '');

                var select = el.parentNode.cloneNode(true);
                el.parentNode.parentNode.replaceChild(select, el.parentNode);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            var campo = document.querySelector('#dataHora');
            if (campo) {
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
            }

            var escolherOutraData = document.querySelector('#escolherOutraData');
            if (escolherOutraData) {
                document.getElementById('escolherOutraData').addEventListener('click', function(e) {
                    e.preventDefault();
                    document.querySelector('.alert-warning').style.display = 'none';
                    document.getElementById('cardAgendamento').style.display = 'block';
                });
            }
        });
    </script>
</body>
</html>