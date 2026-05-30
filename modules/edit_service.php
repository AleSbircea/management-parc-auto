<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id == 0) {
    header("Location: service.php?error=" . urlencode("ID serviciu invalid"));
    exit();
}

// Fetch serviciul din DB
$query = "SELECT * FROM service WHERE id = $id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header("Location: service.php?error=" . urlencode("Service-ul nu a fost găsit"));
    exit();
}

$service = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Editare Service - AUTO221</title>
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
                <h1>Editare Reparație Service</h1>
                <p>Modifică detaliile reparației.</p>
            </div>
            <a href="service.php" class="btn-add">← Înapoi la Service</a>
        </header>

        <section class="recent-activity">
            <h2>Formularul de Editare</h2>
            
            <form action="service_logic.php" method="POST" class="car-form">
                <input type="hidden" name="id" value="<?php echo $service['id']; ?>">
                
                <div class="form-grid">
                    <div class="form-group">
                        <label>Mașină</label>
                        <select name="masina_id" required>
                            <option value="">-- Alege mașină --</option>
                            <?php
                            $masini = mysqli_query($conn, "SELECT id_masina, marca, model FROM masini");
                            while($m = mysqli_fetch_assoc($masini)) {
                                $selected = ($m['id_masina'] == $service['masina_id']) ? 'selected' : '';
                                echo "<option value='" . $m['id_masina'] . "' $selected>" . htmlspecialchars($m['marca'] . " " . $m['model']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Data Intrare</label>
                        <input type="date" name="data_intrare" value="<?php echo $service['data_intrare']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Data Ieșire</label>
                        <input type="date" name="data_iesire" value="<?php echo $service['data_iesire'] ?? ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Motiv</label>
                        <select name="motiv" required>
                            <option value="revizie" <?php echo ($service['motiv'] == 'revizie') ? 'selected' : ''; ?>>Revizie</option>
                            <option value="reparatie" <?php echo ($service['motiv'] == 'reparatie') ? 'selected' : ''; ?>>Reparație</option>
                            <option value="accident" <?php echo ($service['motiv'] == 'accident') ? 'selected' : ''; ?>>Accident</option>
                            <option value="anvelope" <?php echo ($service['motiv'] == 'anvelope') ? 'selected' : ''; ?>>Anvelope</option>
                            <option value="ITP" <?php echo ($service['motiv'] == 'ITP') ? 'selected' : ''; ?>>ITP</option>
                            <option value="altele" <?php echo ($service['motiv'] == 'altele') ? 'selected' : ''; ?>>Altele</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>KM la Intrare</label>
                        <input type="number" name="km_la_intrare" value="<?php echo $service['km_la_intrare']; ?>" min="0" required>
                    </div>
                    <div class="form-group">
                        <label>Cost (RON)</label>
                        <input type="number" name="cost" value="<?php echo $service['cost'] ?? ''; ?>" step="0.01" min="0">
                    </div>
                    <div class="form-group">
                        <label>Atelier</label>
                        <input type="text" name="atelier" value="<?php echo htmlspecialchars($service['atelier'] ?? ''); ?>" placeholder="Ex: Service XYZ">
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" required>
                            <option value="programat" <?php echo ($service['status'] == 'programat') ? 'selected' : ''; ?>>Programat</option>
                            <option value="in_lucru" <?php echo ($service['status'] == 'in_lucru') ? 'selected' : ''; ?>>În Lucru</option>
                            <option value="finalizat" <?php echo ($service['status'] == 'finalizat') ? 'selected' : ''; ?>>Finalizat</option>
                        </select>
                    </div>
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label>Descriere</label>
                        <textarea name="descriere" rows="3" required><?php echo htmlspecialchars($service['descriere']); ?></textarea>
                    </div>
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label>Lucrări Efectuate</label>
                        <textarea name="lucrari_efectuate" rows="3"><?php echo htmlspecialchars($service['lucrari_efectuate'] ?? ''); ?></textarea>
                    </div>
                </div>
                
                <div class="form-actions">
                    <input type="submit" name="edit_service" value="Salvează Modificări" class="btn-submit">
                    <a href="service.php" class="btn-cancel">Anulează</a>
                </div>
            </form>

            <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #222;">
                <h3 style="color: #d63031; margin-bottom: 15px;">⚠️ Zonă Periculoasă!</h3>
                <p style="margin-bottom: 15px; color: #888;">Șterge această reparație din sistem. Atenție: Aceasta nu poate fi anulată!</p>
                <a href="service_logic.php?delete=<?php echo $service['id']; ?>" 
                class="btn-delete" 
                onclick="return confirm('Sigur vrei să ștergi această reparație?');">
                🗑️ Șterge reparație
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