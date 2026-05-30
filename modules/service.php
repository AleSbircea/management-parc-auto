<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php"); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Gestiune Service - AUTO221</title>
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
                <li><a href="service.php" class="active">Service</a></li>
                <li><a href="../logout.php">Deconectare</a></li>
            </ul>
        </nav>
    </aside>

    <main class="content">
        <?php if (isset($_GET['success'])): ?>
            <div class="alert success">✅ Reparație a fost adăugat cu succes!</div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert error">❌ <?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>
        
        <header class="page-header">
            <div>
                <h1>Gestiune Service</h1>
                <p>Urmărește reparații, revizii și servicii efectuate.</p>
            </div>
            <a href="#add-service-section" class="btn-add">+ Adaugă Reparație</a>
        </header>

        <section class="recent-activity">
            <div class="table-header-tools">
                <h2>Reparațiile Recente</h2>
                <input type="text" placeholder="Caută după mașină..." class="search-box">
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>Mașină</th>
                        <th>Data Intrare</th>
                        <th>Data Ieșire</th>
                        <th>Motiv</th>
                        <th>Cost</th>
                        <th>Status</th>
                        <th>Acțiuni</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $query = "SELECT s.*, m.marca, m.model FROM service s 
                              JOIN masini m ON s.masina_id = m.id_masina 
                              ORDER BY s.id DESC";
                    $result = mysqli_query($conn, $query);

                    if (mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_assoc($result)) {
                    ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($row['marca'] . " " . $row['model']); ?></strong></td>
                                <td><?php echo htmlspecialchars($row['data_intrare']); ?></td>
                                <td><?php echo htmlspecialchars($row['data_iesire'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($row['motiv']); ?></td>
                                <td><?php echo htmlspecialchars($row['cost'] ?? '0') . " RON"; ?></td>
                                
                                <td>
                                    <span class="badge <?php echo ($row['status'] == 'finalizat') ? 'activa' : 'inactiva'; ?>">
                                        <?php echo htmlspecialchars($row['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="edit_service.php?id=<?php echo $row['id']; ?>" class="action-link edit">Editează</a>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='7' style='text-align:center;'>Nu există reparații înregistrate în sistem.</td></tr>";
                    }
                    ?>

                </tbody>
            </table>
        </section>

        <section class="recent-activity" id="add-service-section" style="margin-top: 40px;">
            <h2>Adaugă Reparație Nouă</h2>
            
            <form action="service_logic.php" method="POST" class="car-form">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Mașină</label>
                        <select name="masina_id" required>
                            <option value="">-- Alege mașină --</option>
                            <?php
                            $masini = mysqli_query($conn, "SELECT id_masina, marca, model FROM masini WHERE status = 'activa' OR status = 'service'");
                            while($m = mysqli_fetch_assoc($masini)) {
                                echo "<option value='" . $m['id_masina'] . "'>" . htmlspecialchars($m['marca'] . " " . $m['model']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Data Intrare</label>
                        <input type="date" name="data_intrare" required>
                    </div>
                    <div class="form-group">
                        <label>Data Ieșire</label>
                        <input type="date" name="data_iesire">
                    </div>
                    <div class="form-group">
                        <label>Motiv</label>
                        <select name="motiv" required>
                            <option value="">-- Alege --</option>
                            <option value="revizie">Revizie</option>
                            <option value="reparatie">Reparație</option>
                            <option value="accident">Accident</option>
                            <option value="anvelope">Anvelope</option>
                            <option value="ITP">ITP</option>
                            <option value="altele">Altele</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>KM la Intrare</label>
                        <input type="number" name="km_la_intrare" min="0" required>
                    </div>
                    <div class="form-group">
                        <label>Cost (RON)</label>
                        <input type="number" name="cost" step="0.01" min="0">
                    </div>
                    <div class="form-group">
                        <label>Atelier</label>
                        <input type="text" name="atelier" placeholder="Ex: Service XYZ">
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" required>
                            <option value="programat">Programat</option>
                            <option value="in_lucru">În Lucru</option>
                            <option value="finalizat">Finalizat</option>
                        </select>
                    </div>
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label>Descriere</label>
                        <textarea name="descriere" placeholder="Descriere problemă..." rows="3" required></textarea>
                    </div>
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label>Lucrări Efectuate</label>
                        <textarea name="lucrari_efectuate" placeholder="Ce s-a reparat..." rows="3"></textarea>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" name="add_service" class="btn-submit">Salvează Reparație</button>
                </div>
            </form>
        </section>
    </main>
</div>
</body>
</html>