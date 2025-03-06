<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cabeleleila Leila Salão de Beleza</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('/images/logo.jpg');
            background-size: cover;
            background-position: center; 
            background-repeat: no-repeat; 
            margin: 0; 
            padding: 0; 
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .content {
            text-align: center;
            max-width: 800px;
            width: 100%;
            background-color: rgba(255, 255, 255, 0.5);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }
        .logo {
            max-width: 200px;
            margin-bottom: 2rem;
        }
        .btn {
            margin: 0.5rem 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="content">
        <h1 class="mb-4">Cabeleleila Leila Salão de Beleza</h1>
        <h4 class="mb-4">Cabelo, unhas, hidratação e unhas!</h4>
        <a href="{{ route('cliente.login') }}" class="btn btn-primary btn-lg">Sou Cliente</a>
        <a href="{{ route('funcionario.login') }}" class="btn btn-secondary btn-lg">Sou Funcionário</a>
    </div>
</body>
</html>
