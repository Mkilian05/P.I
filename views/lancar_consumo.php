<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/database.php';

$page = 'consumo';
$id_usuario = $_SESSION['id_usuario'];

$erros = $_SESSION['erros'] ?? [];
unset($_SESSION['erros']);
$sucesso = $_SESSION['sucesso'] ?? null;
unset($_SESSION['sucesso']);

// 1. LISTAR DISPOSITIVOS DO USUÁRIO PARA O DROPDOWN
// Trazemos também a potência para ajudar no cálculo (opcional visualmente)
$sql = "
    SELECT 
        d.id_dispositivo, 
        d.nome_dispositivo, 
        d.potencia_dispositivo,
        a.id_ambiente,
        a.nome_ambiente,
        c.nome_casa
    FROM dispositivos d
    JOIN ambiente_dispositivo ad ON d.id_dispositivo = ad.fk_disp_id_disp
    JOIN ambiente a ON ad.fk_ambi_id_ambi = a.id_ambiente
    JOIN casa c ON a.fk_casa_id_casa = c.id_casa
    WHERE c.fk_usuario_id_usuario = ?
    AND (d.is_deleted IS NULL OR d.is_deleted = 0)
    ORDER BY c.nome_casa, a.nome_ambiente, d.nome_dispositivo
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$dispositivos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

include_once __DIR__ . '/../includes/header.php';
include_once __DIR__ . '/../includes/sidebar-nav.php';
?>

<main class="dashboard-content fade-in">
    <header class="top-header">
        <h1>Registrar Consumo</h1>
        <p>Informe quanto tempo cada aparelho ficou ligado.</p>
    </header>

    <?php if (!empty($erros)): ?>
        <div class="alerta erro"><?php foreach ($erros as $erro): echo "<p>$erro</p>"; endforeach; ?></div>
    <?php endif; ?>
    <?php if ($sucesso): ?>
        <div class="alerta sucesso"><p><?php echo htmlspecialchars($sucesso); ?></p></div>
    <?php endif; ?>

    <section class="card" style="max-width: 600px; margin: 0 auto;">
        
        <form action="../config/processa_consumo.php" method="POST">
            
            <div class="form-group">
                <label>Data do Uso</label>
                <input type="date" name="data_uso" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <br>
            <div class="form-group">
                <label>Selecione o Dispositivo</label>
                <select name="selecao_dispositivo" required>
                    <option value="" disabled selected>Escolha um aparelho...</option>
                    <?php foreach ($dispositivos as $disp): ?>
                        <option value="<?php echo $disp['id_dispositivo'] . '|' . $disp['id_ambiente']; ?>">
                            <?php echo htmlspecialchars($disp['nome_dispositivo']); ?> 
                            (<?php echo htmlspecialchars($disp['nome_ambiente']); ?> - <?php echo htmlspecialchars($disp['nome_casa']); ?>) 
                            - <?php echo $disp['potencia_dispositivo']; ?>W
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <br>
            <div class="form-group">
                <label>Tempo de Uso (Horas:Minutos)</label>
                <input type="time" name="tempo_uso" required>
                <br>
                <small style="color:#666;">Ex: 01:30 para uma hora e meia.</small>
            </div>
            <br>
            <div style="margin-top: 20px; text-align: right;">
                <button type="submit" class="btn-primary">Registrar</button>
            </div>

        </form>
    </section>
</main>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>