<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

$id_masina = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id_masina == 0) {
    header("Location: masini.php?error=" . urlencode("ID mașină invalid"));
    exit();
}

// Fetch mașina din DB
$query = "SELECT * FROM masini WHERE id_masina = $id_masina";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header("Location: masini.php?error=" . urlencode("Mașina nu a fost găsită"));
    exit();
}

$car = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Editare Mașină - AUTO221</title>
    <link rel="stylesheet" href="../assets/style.css?v=<?php echo time(); ?>">
</head>

<body>
<div class="container">
    <aside class="sidebar">
        <h2>AUTO<span>221</span></h2>
        <nav>
            <ul>
                <li><a href="../dashboard.php">Dashboard</a></li>
                <li><a href="masini.php">Mașini</a></li>
                <li><a href="soferi.php">Șoferi</a></li>
                <li><a href="service.php">Service</a></li>
                <li><a href="../logout.php">Deconectare</a></li>
            </ul>
        </nav>
    </aside>

    <main class="content">
        <?php if (isset($_GET['error'])): ?>
            <div class="alert error">❌ <?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>

        <header class="page-header">
            <div>
                <h1>Editare Mașină</h1>
                <p>Modifică detaliile vehiculului.</p>
            </div>
            <a href="masini.php" class="btn-add">← Înapoi la Mașini</a>
        </header>

        <section class="recent-activity">
            <h2>Formularul de Editare</h2>
            
            <form action="masini_logic.php" method="POST" class="car-form">
                <input type="hidden" name="id_masina" value="<?php echo $car['id_masina']; ?>">
                
                <div class="form-grid">
                    <div class="form-group">
                        <label>Marcă auto</label>
                        <input type="text" name="marca" value="<?php echo htmlspecialchars($car['marca']); ?>" placeholder="Ex: Mercedes-Benz" required>
                    </div>
                    <div class="form-group">
                        <label>Model</label>
                        <input type="text" name="model" value="<?php echo htmlspecialchars($car['model']); ?>" placeholder="Ex: C-Class" required>
                    </div>
                    <div class="form-group">
                        <label>Număr Înmatriculare</label>
                        <input type="text" name="numar_inmatriculare" value="<?php echo htmlspecialchars($car['numar_inmatriculare']); ?>" placeholder="Ex: B 123 ABC" required>
                    </div>
                    <div class="form-group">
                        <label>Serie Șasiu (VIN)</label>
                        <input type="text" name="vin" value="<?php echo htmlspecialchars($car['vin']); ?>" placeholder="17 caractere" required>
                    </div>
                    <div class="form-group">
                        <label>An Fabricație</label>
                        <input type="number" name="an_fabricatie" value="<?php echo $car['an_fabricatie']; ?>" placeholder="Ex: 2020" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Kilometri Actuali</label>
                        <input type="number" name="km_actuali" value="<?php echo $car['km_actuali']; ?>" placeholder="Ex: 45000" min="0" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Sezon Anvelope</label>
                        <select name="sezon_anvelope" required>
                            <option value="vara" <?php echo ($car['sezon_anvelope'] == 'vara') ? 'selected' : ''; ?>>Vară</option>
                            <option value="iarna" <?php echo ($car['sezon_anvelope'] == 'iarna') ? 'selected' : ''; ?>>Iarnă</option>
                            <option value="all_season" <?php echo ($car['sezon_anvelope'] == 'all_season') ? 'selected' : ''; ?>>All-Season</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" required>
                            <option value="activa" <?php echo ($car['status'] == 'activa') ? 'selected' : ''; ?>>Activă (Disponibilă)</option>
                            <option value="service" <?php echo ($car['status'] == 'service') ? 'selected' : ''; ?>>În Service</option>
                            <option value="indisponibila" <?php echo ($car['status'] == 'indisponibila') ? 'selected' : ''; ?>>Indisponibilă</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-actions">
                    <input type="submit" name="edit_car" value="Salvează Modificări" class="btn-submit">
                    <a href="masini.php" class="btn-cancel">Anulează</a>
                </div>
            </form>

            <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #222;">
                <h3 style="color: #d63031; margin-bottom: 15px;">⚠️ Zonă Periculoasă!</h3>
                <p style="margin-bottom: 15px; color: #888;">Șterge această mașină din sistem. Atenție: Aceasta nu poate fi anulată!</p>
                <a href="masini_logic.php?delete=<?php echo $car['id_masina']; ?>" 
                class="btn-delete" 
                onclick="return confirm('Sigur vrei să ștergi această mașină?');">
                🗑️ Șterge Mașina
                </a>
            </div>
        </section>
    </main>
</div>

<style>
.btn-cancel {
    display: inline-block;
    padding: 12px 20px;
    background-color: #444;
    color: #fff;
    border-radius: 4px;
    text-decoration: none;
    font-weight: 600;
    transition: 0.3s;
    margin-left: 10px;
}

.btn-cancel:hover {
    background-color: #555;
}

.btn-delete {
    display: inline-block;
    padding: 10px 15px;
    background-color: #d63031;
    color: #fff;
    border-radius: 4px;
    text-decoration: none;
    font-weight: 600;
    transition: 0.3s;
}

.btn-delete:hover {
    background-color: #c92a25;
}
</style>

</body>
</html>