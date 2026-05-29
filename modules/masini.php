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
    <title>Gestiune Mașini - AUTO221</title>
    <link rel="stylesheet" href="../assets/style.css?v=<?php echo time(); ?>">
</head>

<body>
<div class="container">
    <aside class="sidebar">
        <h2>AUTO<span>221</span></h2>
        <nav>
            <ul>
                <li><a href="../dashboard.php">Dashboard</a></li>
                <li><a href="masini.php" class="active">Mașini</a></li>
                <li><a href="soferi.php">Șoferi</a></li>
                <li><a href="service.php">Service</a></li>
                <li><a href="../logout.php">Deconectare</a></li>
            </ul>
        </nav>
    </aside>

    <main class="content">
        <?php if (isset($_GET['success'])): ?>
            <div class="alert success">✅ Mașina a fost adăugată cu succes!</div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert error">❌ <?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>
        
        <header class="page-header">
            <div>
                <h1>Gestiune Flotă Auto</h1>
                <p>Adaugă, editează și monitorizează vehiculele din parcul tău auto.</p>
            </div>
            <a href="#add-car-section" class="btn-add">+ Adaugă Mașină</a>
        </header>

        <section class="recent-activity">
            <div class="table-header-tools">
                <h2>Toate Vehiculele</h2>
                <input type="text" placeholder="Caută după nr. înmatriculare..." class="search-box">
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>Marcă & Model</th>
                        <th>Nr. Înmatriculare</th>
                        <th>VIN / Șasiu</th>
                        <th>An Fabricație</th>
                        <th>Km Actuali</th> <th>Anvelope</th>   <th>Status</th>
                        <th>Acțiuni</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $query = "SELECT * FROM masini ORDER BY id_masina DESC";
                    $result = mysqli_query($conn, $query);

                    if (mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_assoc($result)) {
                    ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($row['marca'] . " " . $row['model']); ?></strong></td>
                                <td><span class="plate-number"><?php echo htmlspecialchars($row['numar_inmatriculare']); ?></span></td>
                                <td><?php echo htmlspecialchars($row['vin']); ?></td>
                                <td><?php echo htmlspecialchars($row['an_fabricatie']); ?></td>
                                
                                <td>
                                    <span class="km-display">
                                        <?php echo number_format($row['km_actuali']); ?> km
                                    </span>
                                </td>
                                
                                <td>
                                    <span class="badge-tire <?php echo htmlspecialchars($row['sezon_anvelope']); ?>">
                                        <?php 
                                            if($row['sezon_anvelope'] == 'Vara') echo 'Vară';
                                            elseif($row['sezon_anvelope'] == 'Iarna') echo 'Iarnă';
                                            else echo 'All-Season';
                                        ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="badge <?php echo ($row['status'] == 'activa') ? 'activa' : 'inactiva'; ?>">
                                        <?php echo htmlspecialchars($row['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="edit_masini.php?id=<?php echo $row['id_masina']; ?>" class="action-link edit">Editează</a>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='8' style='text-align:center;'>Nu există mașini înregistrate în sistem.</td></tr>";
                    }
                    ?>

                </tbody>
            </table>
        </section>

        <section class="recent-activity" id="add-car-section" style="margin-top: 40px;">
            <h2>Înregistrare Vehicul Nou</h2>
            
            <form action="masini_logic.php" method="POST" class="car-form">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Marcă auto</label>
                        <input type="text" name="marca" placeholder="Ex: Mercedes-Benz" required>
                    </div>
                    <div class="form-group">
                        <label>Model</label>
                        <input type="text" name="model" placeholder="Ex: C-Class" required>
                    </div>
                    <div class="form-group">
                        <label>Număr Înmatriculare</label>
                        <input type="text" name="numar_inmatriculare" placeholder="Ex: B 123 ABC" required>
                    </div>
                    <div class="form-group">
                        <label>Serie Șasiu (VIN)</label>
                        <input type="text" name="vin" placeholder="17 caractere" required>
                    </div>
                    <div class="form-group">
                        <label>An Fabricație</label>
                        <input type="number" name="an_fabricatie" placeholder="Ex: 2020" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Kilometri Actuali</label>
                        <input type="number" name="km_actuali" placeholder="Ex: 45000" min="0" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Sezon Anvelope</label>
                        <select name="sezon_anvelope">
                            <option value="Vara">Vară</option>
                            <option value="Iarna">Iarnă</option>
                            <option value="All-Season">All-Season</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Status Inițial</label>
                        <select name="status">
                            <option value="activa">Activă (Disponibilă)</option>
                            <option value="service">În Service</option>
                            <option value="indisponibila">Indisponibilă</option>    
                        </select>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" name="add_car" class="btn-submit">Salvează în Parcul Auto</button>
                </div>
            </form>
        </section>
    </main>
</div>
</body>
</html>