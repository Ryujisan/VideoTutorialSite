<?php
// sessie
session_start();
// config
require_once("../config.inc.php");

// functie om een error te geven
function returnError($error = "onbekende error")
{
    // zet de error data
    $data['success'] = false;
    $data['error'] = $error;

    // zet de data op de pagina
    echo json_encode($data);

    // stop met het laden van de pagina
    die();
}

// * variabelen
// return data
$data = [];
$data['success'] = false;
// form waardes
$course;
$hoofdstuk;
$video;

// * validatie
// test of er een sessie bestaat
if (!$_SESSION['ingelogd']) {
    // er is niet ingelogd, geef een error
    returnError("Geen sessie (meer) actief");
}

// test of de verplichte data is ingevuld
if (
    !isset($_POST['course']) ||
    !isset($_POST['hoofdstuk']) ||
    !isset($_POST['video'])
) {
    // niet alle verplichte waardes zijn opgegeven, geef een error
    returnError("Niet alle data is doorgestuurd");
} else {
    // de waardes zijn wel ingevuld, sla ze op in variabelen
    $course = $_POST['course'];
    $hoofdstuk = $_POST['hoofdstuk'];
    $video = $_POST['video'];
}

// bereid de query voor
$query = "DELETE FROM `hoofdstuk_video` WHERE `hoofdstuk_video`.`id` = ? AND `hoofdstuk_video`.`courseId` = ? AND `hoofdstuk_video`.`hoofdstukId` = ?";
$stmt = $mysqli->stmt_init();
if (!$stmt->prepare($query)) {
    returnError("serverError");
};
if (!$stmt->bind_param("iii", $video, $course, $hoofdstuk)) {
    returnError("ServerError");
}
// voer de query uit
if ($stmt->execute()) {
    $data['success'] = true;
    echo json_encode($data);
} else {
    returnError("Database Error");
}
$stmt->close();
