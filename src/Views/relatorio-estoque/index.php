<?php 
require __DIR__ . '/../layout/header.php'; 
?>

<div class="container">
    <h1 class="my-4">Relatório de Estoque</h1>
    <p class="lead">Lista de produtos com destaque para estoque baixo.</p>

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>Nome do Produto</th>
                <th class="text-center" style="width: 150px;">Estoque Mínimo</th>
                <th class="text-center" style="width: 150px;">Estoque Atual</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produtos as $produto): ?>
                
                <?php
                // REQUISITO #3: Define a classe de destaque (vermelho)
                $classeDestaque = '';
                if ($produto->estoque <= $produto->estoque_minimo) {
                    $classeDestaque = 'table-danger'; // Classe do Bootstrap para fundo vermelho
                }
                ?>

                <tr class="<?php echo $classeDestaque; ?>">
                    
                    <td><?php echo htmlspecialchars($produto->nome); ?></td>
                    
                    <td class="text-center">
                        <?php echo htmlspecialchars($produto->estoque_minimo); ?>
                    </td>
                    
                    <td class="text-center fw-bold">
                        <?php echo htmlspecialchars($produto->estoque); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php 
require __DIR__ . '/../layout/footer.php'; 
?>