<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['add_car'])) {

    $marca               = trim(mysqli_real_escape_string($conn, $_POST['marca']));
    $model               = trim(mysqli_real_escape_string($conn, $_POST['model']));
    $numar_inmatriculare = trim(mysqli_real_escape_string($conn, $_POST['numar_inmatriculare']));
    $vin                 = strtoupper(trim(mysqli_real_escape_string($conn, $_POST['vin'])));
    $an_fabricatie       = (int) $_POST['an_fabricatie'];
    $km_actuali          = (int) $_POST['km_actuali'];
    $sezon_anvelope      = $_POST['sezon_anvelope'];
    $status              = $_POST['status'];

    
    $errors = [];

    if (empty($marca) || empty($model)) {
        $errors[] = "Marca și modelul sunt obligatorii.";
    }

    if (strlen($vin) !== 17) {
        $errors[] = "VIN-ul trebuie să aibă exact 17 caractere.";
    }

    if ($an_fabricatie < 1901 || $an_fabricatie > 2099) {
        $errors[] = "Anul fabricației este invalid.";
    }

    if ($km_actuali < 0) {
        $errors[] = "Kilometrajul nu poate fi negativ.";
    }

    if (!in_array($sezon_anvelope, ['vara', 'iarna', 'allseason'])) {
        $errors[] = "Sezon anvelope invalid.";
    }

    if (!in_array($status, ['activa', 'service', 'indisponibila'])) {
        $errors[] = "Status invalid.";
    }

    if (!empty($errors)) {
        $msg = implode(" ", $errors);
        header("Location: masini.php?error=" . urlencode($msg));
        exit();
    }
    $checkVin = mysqli_query($conn, "SELECT id_masina FROM masini WHERE vin = '$vin'");
    if (mysqli_num_rows($checkVin) > 0) {
        header("Location: masini.php?error=" . urlencode("Un vehicul cu acest VIN există deja în sistem."));
        exit();
    }

    
    $checkPlate = mysqli_query($conn, "SELECT id_masina FROM masini WHERE numar_inmatriculare = '$numar_inmatriculare'");
    if (mysqli_num_rows($checkPlate) > 0) {
        header("Location: masini.php?error=" . urlencode("Numărul de înmatriculare există deja în sistem."));
        exit();
    }
    $query = "INSERT INTO masini (marca, model, numar_inmatriculare, vin, an_fabricatie, km_actuali, sezon_anvelope, status)
              VALUES ('$marca', '$model', '$numar_inmatriculare', '$vin', $an_fabricatie, $km_actuali, '$sezon_anvelope', '$status')";

    if (mysqli_query($conn, $query)) {
        header("Location: masini.php?success=1");
    } else {
        $err = urlencode("Eroare DB: " . mysqli_error($conn));
        header("Location: masini.php?error=$err");
    }
    exit();
}
header("Location: masini.php");
exit();