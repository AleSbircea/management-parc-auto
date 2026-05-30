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
    <title>Gestiune Șoferi - AUTO221</title>
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
                <li><a href="soferi.php" class="active">Șoferi</a></li>
                <li><a href="service.php">Service</a></li>
                <li><a href="../logout.php">Deconectare</a></li>
            </ul>
        </nav>
    </aside>

    <main class="content">
        <?php if (isset($_GET['success'])): ?>
            <div class="alert success">✅ Șoferul a fost adăugat cu succes!</div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert error">❌ <?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>
        
        <header class="page-header">
            <div>
                <h1>Gestiune Șoferi</h1>
                <p>Adaugă, editează și monitorizează șoferii din flotă.</p>
            </div>
            <a href="#add-sofer-section" class="btn-add">+ Adaugă Șofer</a>
        </header>

        <section class="recent-activity">
            <div class="table-header-tools">
                <h2>Toți Șoferii</h2>
                <input type="text" placeholder="Caută după nume sau CNP..." class="search-box">
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>Nume</th>
                        <th>CNP</th>
                        <th>Telefon</th>
                        <th>Permis</th>
                        <th>Valabilitate</th>
                        <th>Status</th>
                        <th>Acțiuni</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $query = "SELECT * FROM soferi ORDER BY id DESC";
                    $result = mysqli_query($conn, $query);

                    if (mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_assoc($result)) {
                    ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($row['nume'] . " " . $row['prenume']); ?></strong></td>
                                <td><?php echo htmlspecialchars($row['cnp']); ?></td>
                                <td><?php echo htmlspecialchars($row['telefon'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($row['permis_nr']); ?></td>
                                <td><?php echo htmlspecialchars($row['permis_valabilitate']); ?></td>
                                
                                <td>
                                    <span class="badge <?php echo ($row['status'] == 'activ') ? 'activa' : 'inactiva'; ?>">
                                        <?php echo htmlspecialchars($row['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="edit_soferi.php?id=<?php echo $row['id']; ?>" class="action-link edit">Editează</a>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='7' style='text-align:center;'>Nu există șoferi înregistrați în sistem.</td></tr>";
                    }
                    ?>

                </tbody>
            </table>
        </section>

        <section class="recent-activity" id="add-sofer-section" style="margin-top: 40px;">
            <h2>Înregistrare Șofer Nou</h2>
            
            <form action="soferi_logic.php" method="POST" class="car-form">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Nume</label>
                        <input type="text" name="nume" placeholder="Ex: Popescu" required>
                    </div>
                    <div class="form-group">
                        <label>Prenume</label>
                        <input type="text" name="prenume" placeholder="Ex: Ioan" required>
                    </div>
                    <div class="form-group">
                        <label>CNP (13 cifre)</label>
                        <input type="text" name="cnp" placeholder="1234567890123" maxlength="13" required>
                    </div>
                    <div class="form-group">
                        <label>Număr Permis</label>
                        <input type="text" name="permis_nr" placeholder="Ex: RO2024001" required>
                    </div>
                    <div class="form-group">
                        <label>Valabilitate Permis</label>
                        <input type="date" name="permis_valabilitate" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Categoria Permis</label>
                        <select name="categorie_permis" required>
                            <option value="">-- Alege --</option>
                            <option value="AM">AM</option>
                            <option value="A1">A1</option>
                            <option value="A2">A2</option>
                            <option value="A">A</option>
                            <option value="B1">B1</option>
                            <option value="B">B (standard)</option>
                            <option value="BE">BE</option>
                            <option value="C1">C1</option>
                            <option value="C">C</option>
                            <option value="CE">CE</option>
                            <option value="D">D</option>
                            <option value="DE">DE</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Telefon (0712345678 sau +40712345678)</label>
                        <input type="text" name="telefon" placeholder="0712345678" maxlength="13">
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="sofer@example.com">
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" required>
                            <option value="activ">Activ</option>
                            <option value="inactiv">Inactiv</option>
                            <option value="suspendat">Suspendat</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" name="add_sofer" class="btn-submit">Salvează Șoferul</button>
                </div>
            </form>
        </section>
    </main>
</div>
</body>
</html>