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
            <div class="alert success">✅ Șoferul a fost înregistrat cu succes!</div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert error">❌ <?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>
        
        <header class="page-header">
            <div>
                <h1>Gestiune Șoferi</h1>
                <p>Monitorizează personalul, permisele de conducere și statusul activității.</p>
            </div>
            <a href="#add-driver-section" class="btn-add">+ Adaugă Șofer</a>
        </header>

        <section class="recent-activity">
            <div class="table-header-tools">
                <h2>Personal Angajat</h2>
                <input type="text" placeholder="Caută șofer după nume..." class="search-box">
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>Nume & Prenume</th>
                        <th>CNP</th>
                        <th>Telefon</th>
                        <th>Nr. Permis</th>
                        <th>Status</th>
                        <th>Acțiuni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Interogarea bazei de date
                    $query = "SELECT * FROM soferi ORDER BY id_sofer DESC";
                    $result = mysqli_query($conn, $query);

                    if ($result && mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_assoc($result)) {
                    ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($row['nume'] . " " . $row['prenume']); ?></strong></td>
                                <td><?php echo htmlspecialchars($row['cnp']); ?></td>
                                <td><?php echo htmlspecialchars($row['telefon']); ?></td>
                                <td><span class="km-display"><?php echo htmlspecialchars($row['nr_permis']); ?></span></td>
                                <td>
                                    <span class="badge <?php echo ($row['status'] == 'activ') ? 'activa' : 'inactiva'; ?>">
                                        <?php echo htmlspecialchars($row['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="edit_sofer.php?id=<?php echo $row['id_sofer']; ?>" class="action-link edit">Editează</a>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                    
                        ?>
                        <tr>
                            <td><strong>Popescu Ionuț</strong></td>
                            <td>1950102123456</td>
                            <td>0722123456</td>
                            <td><span class="km-display">B0032145D</span></td>
                            <td><span class="badge activa">activ</span></td>
                            <td><a href="#" class="action-link edit">Editează</a></td>
                        </tr>
                        <tr>
                            <td><strong>Dumitrescu Andrei</strong></td>
                            <td>1980514654321</td>
                            <td>0733987654</td>
                            <td><span class="km-display">SB009854A</span></td>
                            <td><span class="badge inactiva">concediu</span></td>
                            <td><a href="#" class="action-link edit">Editează</a></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </section>

        <section class="recent-activity" id="add-driver-section" style="margin-top: 40px;">
            <h2>Înregistrare Șofer Nou</h2>
            
            <form action="soferi_logic.php" method="POST" class="car-form">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Nume</label>
                        <input type="text" name="nume" placeholder="Ex: Popescu" required>
                    </div>
                    <div class="form-group">
                        <label>Prenume</label>
                        <input type="text" name="prenume" placeholder="Ex: Ionut" required>
                    </div>
                    <div class="form-group">
                        <label>CNP</label>
                        <input type="text" name="cnp" placeholder="13 caractere" maxlength="13" required>
                    </div>
                    <div class="form-group">
                        <label>Număr Telefon</label>
                        <input type="text" name="telefon" placeholder="Ex: 07xx xxx xxx" required>
                    </div>
                    <div class="form-group">
                        <label>Număr Permis</label>
                        <input type="text" name="nr_permis" placeholder="Ex: B00xxxxxx" required>
                    </div>
                    <div class="form-group">
                        <label>Status Inițial</label>
                        <select name="status">
                            <option value="activ">Activ (Disponibil)</option>
                            <option value="concediu">În Concediu</option>
                            <option value="suspendat">Permis Suspendat</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" name="add_driver" class="btn-submit">Înregistrează Șoferul</button>
                </div>
            </form>
        </section>
    </main>
</div>
</body>
</html>