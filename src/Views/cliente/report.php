<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Clientes</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
        }
        h1 {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Relatório de Clientes</h1>

    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>CPF/CNPJ</th>
                <th>E-mail</th>
                <th>Telefone</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clientes as $cliente): ?>
                <tr>
                    <td><?php echo htmlspecialchars($cliente->nome); ?></td>
                    <td><?php echo htmlspecialchars($cliente->cpf_cnpj); ?></td>
                    <td><?php echo htmlspecialchars($cliente->email); ?></td>
                    <td><?php echo htmlspecialchars($cliente->telefone); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>