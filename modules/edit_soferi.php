<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id == 0) {
    header("Location: soferi.php?error=" . urlencode("ID șofer invalid"));
    exit();
}

// Fetch șoferul din DB
$query = "SELECT * FROM soferi WHERE id = $id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header("Location: soferi.php?error=" . urlencode("Șoferul nu a fost găsit"));
    exit();
}

$sofer = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Editare Șofer - AUTO221</title>
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
                <h1>Editare Șofer</h1>
                <p>Modifică detaliile șoferului.</p>
            </div>
            <a href="soferi.php" class="btn-add">← Înapoi la Șoferi</a>
        </header>

        <section class="recent-activity">
            <h2>Formularul de Editare</h2>
            
            <form action="soferi_logic.php" method="POST" class="car-form">
                <input type="hidden" name="id" value="<?php echo $sofer['id']; ?>">
                
                <div class="form-grid">
                    <div class="form-group">
                        <label>Nume</label>
                        <input type="text" name="nume" value="<?php echo htmlspecialchars($sofer['nume']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Prenume</label>
                        <input type="text" name="prenume" value="<?php echo htmlspecialchars($sofer['prenume']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>CNP (13 cifre)</label>
                        <input type="text" name="cnp" value="<?php echo htmlspecialchars($sofer['cnp']); ?>" maxlength="13" required>
                    </div>
                    <div class="form-group">
                        <label>Număr Permis</label>
                        <input type="text" name="permis_nr" value="<?php echo htmlspecialchars($sofer['permis_nr']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Valabilitate Permis</label>
                        <input type="date" name="permis_valabilitate" value="<?php echo $sofer['permis_valabilitate']; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Categoria Permis</label>
                        <select name="categorie_permis" required>
                            <option value="AM" <?php echo ($sofer['categorie_permis'] == 'AM') ? 'selected' : ''; ?>>AM</option>
                            <option value="A1" <?php echo ($sofer['categorie_permis'] == 'A1') ? 'selected' : ''; ?>>A1</option>
                            <option value="A2" <?php echo ($sofer['categorie_permis'] == 'A2') ? 'selected' : ''; ?>>A2</option>
                            <option value="A" <?php echo ($sofer['categorie_permis'] == 'A') ? 'selected' : ''; ?>>A</option>
                            <option value="B1" <?php echo ($sofer['categorie_permis'] == 'B1') ? 'selected' : ''; ?>>B1</option>
                            <option value="B" <?php echo ($sofer['categorie_permis'] == 'B') ? 'selected' : ''; ?>>B</option>
                            <option value="BE" <?php echo ($sofer['categorie_permis'] == 'BE') ? 'selected' : ''; ?>>BE</option>
                            <option value="C1" <?php echo ($sofer['categorie_permis'] == 'C1') ? 'selected' : ''; ?>>C1</option>
                            <option value="C" <?php echo ($sofer['categorie_permis'] == 'C') ? 'selected' : ''; ?>>C</option>
                            <option value="CE" <?php echo ($sofer['categorie_permis'] == 'CE') ? 'selected' : ''; ?>>CE</option>
                            <option value="D" <?php echo ($sofer['categorie_permis'] == 'D') ? 'selected' : ''; ?>>D</option>
                            <option value="DE" <?php echo ($sofer['categorie_permis'] == 'DE') ? 'selected' : ''; ?>>DE</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Telefon</label>
                        <input type="text" name="telefon" value="<?php echo htmlspecialchars($sofer['telefon'] ?? ''); ?>" placeholder="0712345678" maxlength="13">
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($sofer['email'] ?? ''); ?>" placeholder="sofer@example.com">
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" required>
                            <option value="activ" <?php echo ($sofer['status'] == 'activ') ? 'selected' : ''; ?>>Activ</option>
                            <option value="inactiv" <?php echo ($sofer['status'] == 'inactiv') ? 'selected' : ''; ?>>Inactiv</option>
                            <option value="suspendat" <?php echo ($sofer['status'] == 'suspendat') ? 'selected' : ''; ?>>Suspendat</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-actions">
                    <input type="submit" name="edit_sofer" value="Salvează Modificări" class="btn-submit">
                    <a href="soferi.php" class="btn-cancel">Anulează</a>
                </div>
            </form>

            <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #222;">
                <h3 style="color: #d63031; margin-bottom: 15px;">⚠️ Zonă Periculoasă!</h3>
                <p style="margin-bottom: 15px; color: #888;">Șterge acest șofer din sistem. Atenție: Aceasta nu poate fi anulată!</p>
                <a href="soferi_logic.php?delete=<?php echo $sofer['id']; ?>" 
                class="btn-delete" 
                onclick="return confirm('Sigur vrei să ștergi acest șofer?');">
                🗑️ Șterge Șoferul
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