<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

if (isset($_POST['add_service'])) {
    $masina_id = (int) $_POST['masina_id'];
    $data_intrare = $_POST['data_intrare'];
    $data_iesire = $_POST['data_iesire'] ?: NULL;
    $motiv = mysqli_real_escape_string($conn, $_POST['motiv']);
    $descriere = mysqli_real_escape_string($conn, $_POST['descriere']);
    $lucrari_efectuate = mysqli_real_escape_string($conn, $_POST['lucrari_efectuate']);
    $cost = $_POST['cost'] ?: NULL;
    $atelier = mysqli_real_escape_string($conn, $_POST['atelier']);
    $km_la_intrare = (int) $_POST['km_la_intrare'];
    $status = $_POST['status'];

    $errors = [];

    // Validare mașină
    $checkMasina = mysqli_query($conn, "SELECT id_masina FROM masini WHERE id_masina = $masina_id");
    if (mysqli_num_rows($checkMasina) == 0) {
        $errors[] = "Mașina nu există în sistem.";
    }

    // Validare date
    if (empty($data_intrare)) {
        $errors[] = "Data intrării este obligatorie.";
    }

    if (!empty($data_iesire) && strtotime($data_iesire) < strtotime($data_intrare)) {
        $errors[] = "Data ieșirii nu poate fi mai devreme decât data intrării.";
    }

    // Validare motiv
    if (!in_array($motiv, ['revizie', 'reparatie', 'accident', 'anvelope', 'ITP', 'altele'])) {
        $errors[] = "Motiv invalid.";
    }

    // Validare descriere
    if (empty($descriere)) {
        $errors[] = "Descrierea este obligatorie.";
    }

    // Validare cost
    if (!empty($cost) && $cost < 0) {
        $errors[] = "Costul nu poate fi negativ.";
    }

    // Validare KM
    if ($km_la_intrare < 0) {
        $errors[] = "Kilometrajul nu poate fi negativ.";
    }

    // Validare status
    if (!in_array($status, ['programat', 'in_lucru', 'finalizat'])) {
        $errors[] = "Status invalid.";
    }

    if (!empty($errors)) {
        $msg = implode(" | ", $errors);
        header("Location: service.php?error=" . urlencode($msg));
        exit();
    }

    $data_iesire_sql = $data_iesire ? "'$data_iesire'" : "NULL";
    $cost_sql = $cost ? $cost : "NULL";
    $lucrari_sql = !empty($lucrari_efectuate) ? "'$lucrari_efectuate'" : "NULL";

    $query = "INSERT INTO service (masina_id, data_intrare, data_iesire, motiv, descriere, lucrari_efectuate, cost, atelier, km_la_intrare, status)
              VALUES ($masina_id, '$data_intrare', $data_iesire_sql, '$motiv', '$descriere', $lucrari_sql, $cost_sql, '$atelier', $km_la_intrare, '$status')";

    if (mysqli_query($conn, $query)) {
        header("Location: service.php?success=1");
    } else {
        $err = urlencode("Eroare: " . mysqli_error($conn));
        header("Location: service.php?error=$err");
    }
    exit();
}

if (isset($_POST['edit_service'])) {
    $id = (int) $_POST['id'];
    $masina_id = (int) $_POST['masina_id'];
    $data_intrare = $_POST['data_intrare'];
    $data_iesire = $_POST['data_iesire'] ?: NULL;
    $motiv = mysqli_real_escape_string($conn, $_POST['motiv']);
    $descriere = mysqli_real_escape_string($conn, $_POST['descriere']);
    $lucrari_efectuate = mysqli_real_escape_string($conn, $_POST['lucrari_efectuate']);
    $cost = $_POST['cost'] ?: NULL;
    $atelier = mysqli_real_escape_string($conn, $_POST['atelier']);
    $km_la_intrare = (int) $_POST['km_la_intrare'];
    $status = $_POST['status'];

    $errors = [];

    // Validare mașină
    $checkMasina = mysqli_query($conn, "SELECT id_masina FROM masini WHERE id_masina = $masina_id");
    if (mysqli_num_rows($checkMasina) == 0) {
        $errors[] = "Mașina nu există în sistem.";
    }

    // Validare date
    if (empty($data_intrare)) {
        $errors[] = "Data intrării este obligatorie.";
    }

    if (!empty($data_iesire) && strtotime($data_iesire) < strtotime($data_intrare)) {
        $errors[] = "Data ieșirii nu poate fi mai devreme decât data intrării.";
    }

    // Validare motiv
    if (!in_array($motiv, ['revizie', 'reparatie', 'accident', 'anvelope', 'ITP', 'altele'])) {
        $errors[] = "Motiv invalid.";
    }

    // Validare descriere
    if (empty($descriere)) {
        $errors[] = "Descrierea este obligatorie.";
    }

    // Validare cost
    if (!empty($cost) && $cost < 0) {
        $errors[] = "Costul nu poate fi negativ.";
    }

    // Validare KM
    if ($km_la_intrare < 0) {
        $errors[] = "Kilometrajul nu poate fi negativ.";
    }

    // Validare status
    if (!in_array($status, ['programat', 'in_lucru', 'finalizat'])) {
        $errors[] = "Status invalid.";
    }

    if (!empty($errors)) {
        $msg = implode(" | ", $errors);
        header("Location: service.php?error=" . urlencode($msg));
        exit();
    }

    $data_iesire_sql = $data_iesire ? "'$data_iesire'" : "NULL";
    $cost_sql = $cost ? $cost : "NULL";
    $lucrari_sql = !empty($lucrari_efectuate) ? "'$lucrari_efectuate'" : "NULL";

    $query = "UPDATE service SET 
              masina_id = $masina_id,
              data_intrare = '$data_intrare',
              data_iesire = $data_iesire_sql,
              motiv = '$motiv',
              descriere = '$descriere',
              lucrari_efectuate = $lucrari_sql,
              cost = $cost_sql,
              atelier = '$atelier',
              km_la_intrare = $km_la_intrare,
              status = '$status'
              WHERE id = $id";

    if (mysqli_query($conn, $query)) {
        header("Location: service.php?success=2");
    } else {
        $err = urlencode("Eroare: " . mysqli_error($conn));
        header("Location: service.php?error=$err");
    }
    exit();
}

if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];

    $check = mysqli_query($conn, "SELECT id FROM service WHERE id = $id");
    if (mysqli_num_rows($check) == 0) {
        header("Location: service.php?error=" . urlencode("Reparația nu a fost găsită."));
        exit();
    }

    $query = "DELETE FROM service WHERE id = $id";

    if (mysqli_query($conn, $query)) {
        header("Location: service.php?success=3");
    } else {
        $err = urlencode("Eroare: " . mysqli_error($conn));
        header("Location: service.php?error=$err");
    }
    exit();
}

header("Location: service.php");
exit();
?>s