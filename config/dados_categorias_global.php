<?php
// config/dados_categorias_global.php
session_start();
header('Content-Type: application/json');

// Mantemos a autenticação apenas para garantir que quem acessa é um usuário logado
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['error' => 'Acesso negado']);
    exit;
}

require_once __DIR__ . '/../config/database.php';

try {
    // CONSULTA GLOBAL (TODOS OS USUÁRIOS)
    // Caminho: historico -> dispositivo -> categoria
    
    $sql = "
        SELECT 
            c.nome_categoria,
            SUM(h.consumo) as total_gasto_wh
        FROM historico_consumo h
        JOIN dispositivos d ON h.fk_disp_histo = d.id_dispositivo
        JOIN categoria_dispositivos c ON d.fk_cat_disp_id_cat = c.id_categoria
        GROUP BY c.id_categoria, c.nome_categoria
        ORDER BY total_gasto_wh DESC
    ";

    $result = $conn->query($sql);

    if (!$result) {
        throw new Exception("Erro SQL: " . $conn->error);
    }

    $labels = [];
    $data = [];

    while ($row = $result->fetch_assoc()) {
        $labels[] = $row['nome_categoria'];
        
        // Converte para kWh
        $val_kwh = $row['total_gasto_wh'] / 1000;
        $data[] = round($val_kwh, 3);
    }

    echo json_encode(['labels' => $labels, 'data' => $data]);

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>