<?php
include 'config/db.php';
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$sql_total = "SELECT COUNT(*) as total FROM masini";
$res_total = mysqli_query($conn, $sql_total);
$total_masini = mysqli_fetch_assoc($res_total)['total'];

$sql_active = "SELECT COUNT(*) as active FROM masini WHERE status = 'activa'";
$res_active = mysqli_query($conn, $sql_active);
$active_masini = mysqli_fetch_assoc($res_active)['active'];

$alerte = 0; 
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Management Parc Auto</title>
    <link rel="stylesheet "href="assets/style.css"> 
</head>

<body>
<div class="container">
    <aside class="sidebar">
        <h2>Parc Auto</h2>
        <nav>
            <ul>
                <li><a href="index.php" class="active">Dashboard</a></li>
                <li><a href="modules/masini.php">Mașini</a></li>
                <li><a href="modules/soferi.php">Șoferi</a></li>
                <li><a href="modules/service.php">Service</a></li>
                <li><a href="logout.php">Deconectare</a></li>
            </ul>
        </nav>
    </aside>

    <main class="content">
        <header>
            <h1>Salut, <?php echo $_SESSION['username']; ?>!</h1>
            <p>Iată situația parcului auto astăzi.</p>
        </header>

        <div class="stats-grid">
            <div class="card">
                <h3>Total Mașini</h3>
                <p class="number"><?php echo $total_masini; ?></p>
            </div>
            <div class="card green">
                <h3>Disponibile</h3>
                <p class="number"><?php echo $active_masini; ?></p>
            </div>
            <div class="card red">
                <h3>Alerte ITP/Asigurări</h3>
                <p class="number"><?php echo $alerte; ?></p>
            </div>
        </div>

        <section class="recent-activity">
            <h2>Mașini Recente</h2>
            <table>
                <thead>
                    <tr>
                        <th>Marcă/Model</th>
                        <th>Nr. Înmatriculare</th>
                        <th>VIN (Șasiu)</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql_recent = "SELECT * FROM masini ORDER BY id_masina DESC LIMIT 5";
                    $res_recent = mysqli_query($conn, $sql_recent);
                    
                    if (mysqli_num_rows($res_recent) > 0) {
                        while($row = mysqli_fetch_assoc($res_recent)) {
                            echo "<tr>";
                            echo "<td>" . $row['marca'] . " " . $row['model'] . "</td>";
                            echo "<td>" . $row['numar_inmatriculare'] . "</td>";
                            echo "<td>" . $row['vin'] . "</td>";
                            echo "<td><span class='badge " . $row['status'] . "'>" . $row['status'] . "</span></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>Nu există mașini înregistrate.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </main>
</div>

</body>
</html>