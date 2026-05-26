<?php
session_start();


$_SESSION['username'] = "Developer"; 
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
                        <th>Status</th>
                        <th>Acțiuni</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Porsche 911 Carrera</strong></td>
                        <td><span class="plate-number">B 221 AUT</span></td>
                        <td>WP0ZZZ99ZLS123456</td>
                        <td>2022</td>
                        <td><span class="badge activa">activa</span></td>
                        <td><a href="#" class="action-link edit">Editează</a></td>
                    </tr>
                    <tr>
                        <td><strong>Audi e-tron GT</strong></td>
                        <td><span class="plate-number">TM 88 AMP</span></td>
                        <td>WAUZZZGEZLB654321</td>
                        <td>2023</td>
                        <td><span class="badge activa">activa</span></td>
                        <td><a href="#" class="action-link edit">Editează</a></td>
                    </tr>
                    <tr>
                        <td><strong>BMW M4 Competition</strong></td>
                        <td><span class="plate-number">CJ 44 RAC</span></td>
                        <td>WBA31AZ000K789102</td>
                        <td>2021</td>
                        <td><span class="badge inactiva">inactiva</span></td>
                        <td><a href="#" class="action-link edit">Editează</a></td>
                    </tr>
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
                        <label>Status Inițial</label>
                        <select name="status">
                            <option value="activa">Activă (Disponibilă)</option>
                            <option value="inactiva">Inactivă (În Service / Daună)</option>
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