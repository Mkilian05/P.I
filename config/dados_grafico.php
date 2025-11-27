<?php
// config/dados_grafico.php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['error' => 'Usuário não logado']);
    exit;
}

require_once __DIR__ . '/../config/database.php';
$id_usuario = $_SESSION['id_usuario'];

try {
    // Busca o consumo em Wh (como está no banco)
    $sql = "
        SELECT 
            d.nome_dispositivo,
            a.nome_ambiente,
            SUM(h.consumo) as total_gasto_wh
        FROM historico_consumo h
        JOIN dispositivos d ON h.fk_disp_histo = d.id_dispositivo
        JOIN ambiente a ON h.fk_ambi_histo = a.id_ambiente
        JOIN casa k ON a.fk_casa_id_casa = k.id_casa
        WHERE k.fk_usuario_id_usuario = ?
        GROUP BY d.id_dispositivo, d.nome_dispositivo, a.nome_ambiente
        ORDER BY total_gasto_wh DESC
        LIMIT 5
    ";

    $stmt = $conn->prepare($sql);
    if(!$stmt) throw new Exception("Erro SQL: " . $conn->error);

    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    $labels = [];
    $data = [];

    while ($row = $result->fetch_assoc()) {
        $labels[] = $row['nome_dispositivo'] . " (" . $row['nome_ambiente'] . ")";
        
        // --- CONVERSÃO PARA KWh ---
        // Dividimos por 1000 para ficar no padrão da conta de luz
        $val_kwh = $row['total_gasto_wh'] / 1000;
        
        // Arredonda para 3 casas (ex: 0.005) para não zerar lâmpadas muito fracas
        $data[] = round($val_kwh, 3); 
    }

    echo json_encode(['labels' => $labels, 'data' => $data]);

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>