<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
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

    if (!in_array($sezon_anvelope, ['Vara', 'Iarna', 'All-Season'])) {
        $errors[] = "Sezon anvelope invalid.";
    }

    if (!in_array($status, ['activa', 'service', 'indisponibila'])) {
        $errors[] = "Status invalid.";
    }

    if (!empty($errors)) {
        $msg = implode(" | ", $errors);
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
        $err = urlencode("Eroare: " . mysqli_error($conn));
        header("Location: masini.php?error=$err");
    }
    exit();
}

if (isset($_POST['edit_car'])) {
    $id_masina           = (int) $_POST['id_masina'];
    $marca               = trim(mysqli_real_escape_string($conn, $_POST['marca']));
    $model               = trim(mysqli_real_escape_string($conn, $_POST['model']));
    $numar_inmatriculare = trim(mysqli_real_escape_string($conn, $_POST['numar_inmatriculare']));
    $vin                 = strtoupper(trim(mysqli_real_escape_string($conn, $_POST['vin'])));
    $an_fabricatie       = (int) $_POST['an_fabricatie'];
    $km_actuali          = (int) $_POST['km_actuali'];
    $sezon_anvelope      = $_POST['sezon_anvelope'];
    $status              = $_POST['status'];

    $errors = [];
    
    if ($an_fabricatie < 1901 || $an_fabricatie > date('Y')) {
    $errors[] = "Anul fabricației trebuie între 1901 și " . date('Y');
    }

    
    if (strlen($vin) !== 17) {
    $errors[] = "VIN trebuie să aibă exact 17 caractere";
    }

    
    $judete = ['AB','AG','AR','AT','B','BC','BH','BN','BR','BS','BT','BV','BZ','CJ','CL','CS','CT','CV','DB','DJ','DL','DN','DR','DS','DV','GJ','GL','GR','GV','HD','HR','IF','IL','IS','IB','JD','JN','MH','MS','MT','MV','NV','NT','OT','PH','PL','PR','PT','RO','RS','RT','RV','SB','SJ','SM','SV','TB','TL','TM','TR','TT','VL','VN','VS','VV'];
    if (!preg_match('/^(' . implode('|', $judete) . ') [0-9]{2,3} [A-Z]{3}$/', $numar_inmatriculare)) {
    $errors[] = "Format număr înmatriculare invalid";
    }

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

    if (!empty($errors)) {
        $msg = implode(" | ", $errors);
        header("Location: masini.php?error=" . urlencode($msg));
        exit();
    }

    $checkVin = mysqli_query($conn, "SELECT id_masina FROM masini WHERE vin = '$vin' AND id_masina != $id_masina");
    if (mysqli_num_rows($checkVin) > 0) {
        header("Location: masini.php?error=" . urlencode("Noul VIN există deja în sistem."));
        exit();
    }

    $checkPlate = mysqli_query($conn, "SELECT id_masina FROM masini WHERE numar_inmatriculare = '$numar_inmatriculare' AND id_masina != $id_masina");
    if (mysqli_num_rows($checkPlate) > 0) {
        header("Location: masini.php?error=" . urlencode("Noul număr de înmatriculare există deja în sistem."));
        exit();
    }

    $query = "UPDATE masini SET 
              marca = '$marca',
              model = '$model',
              numar_inmatriculare = '$numar_inmatriculare',
              vin = '$vin',
              an_fabricatie = $an_fabricatie,
              km_actuali = $km_actuali,
              sezon_anvelope = '$sezon_anvelope',
              status = '$status'
              WHERE id_masina = $id_masina";

    if (mysqli_query($conn, $query)) {
        header("Location: masini.php?success=2");
    } else {
        $err = urlencode("Eroare: " . mysqli_error($conn));
        header("Location: masini.php?error=$err");
    }
    exit();
}

if (isset($_GET['delete'])) {
    $id_masina = (int) $_GET['delete'];

    $check = mysqli_query($conn, "SELECT id_masina FROM masini WHERE id_masina = $id_masina");
    if (mysqli_num_rows($check) == 0) {
        header("Location: masini.php?error=" . urlencode("Mașina nu a fost găsită."));
        exit();
    }

    $query = "DELETE FROM masini WHERE id_masina = $id_masina";

    if (mysqli_query($conn, $query)) {
        header("Location: masini.php?success=3");
    } else {
        $err = urlencode("Eroare: " . mysqli_error($conn));
        header("Location: masini.php?error=$err");
    }
    exit();
}

header("Location: masini.php");
exit();
?>