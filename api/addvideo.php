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
$url;

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
    !isset($_POST['url'])
) {
    // niet alle verplichte waardes zijn opgegeven, geef een error
    returnError("Niet alle data is doorgestuurd");
} else {
    // de waardes zijn wel ingevuld, sla ze op in variabelen
    $course = $_POST['course'];
    $hoofdstuk = $_POST['hoofdstuk'];
    $url = $_POST['url'];
}

// bereid de query voor om het nieuwe id te krijgen
$newIdQuery = "
SELECT MAX(`id`) + 1 AS 'newId' FROM `hoofdstuk_video` WHERE `courseId` = ? AND `hoofdstukId` = ?";
$stmt = $mysqli->stmt_init();
if (!$stmt->prepare($newIdQuery)) {
    returnError("serverError");
};
if (!$stmt->bind_param("ii", $course, $hoofdstuk)) {
    returnError("ServerError");
}
// voer de query uit
if ($stmt->execute()) {
    // sla het niewe id op in een variabel
    $newId = $stmt->get_result()->fetch_array()['newId'];
} else {
    returnError("DatabaseError");
}
$stmt->close();

// als newId null is bestaat er nog geen hoofdstuk, het nieuwe id is 1
if ($newId === null) {
    $newId = 1;
}

// bereid de query voor
$newHoofdstukQuery = "INSERT INTO `hoofdstuk_video` (`id`, `courseId`, `hoofdstukId`, `titel`, `url`, `omschrijving`) VALUES (?, ?, ?, 'Nieuwe Video', ?, '');";
$stmt = $mysqli->stmt_init();
if (!$stmt->prepare($newHoofdstukQuery)) {
    returnError("serverError");
};
if (!$stmt->bind_param("iiis", $newId, $course, $hoofdstuk, $url)) {
    returnError("ServerError");
}
// voer de query uit
if ($stmt->execute()) {
    $data['success'] = true;
    echo json_encode($data);
} else {
    returnError("DatabaseError?");
}
$stmt->close();
