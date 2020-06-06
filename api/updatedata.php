<?php
// sessie
session_start();
// config
require_once("../config.inc.php");

// * functies
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
$data['success'] = true;
// form waardes
$course;
$hoofdstuk;
$video;
$veldType;
$veld;
$waarde;
// conversie arrays
// veld type omgezet naar database tabel naam
$veldTypeNaarTabel = array(
    "course" => "course",
    "hoofdstuk" => "course_hoofdstuk",
    "video" => "hoofdstuk_video"
);
// veld ogmezet naar database kolom naam
$veldNaarKolom = array(
    "course-titel" => "titel",
    "course-zichtbaarheid" => "visibility",
    "course-omschrijving" => "omschrijving",
    "hoofdstuk-titel" => "titel",
    "video-titel" => "titel",
    "video-url" => "url",
    "video-omschrijving" => "omschrijving"
);

// * validatie
// test of er een sessie bestaat
if (!$_SESSION['ingelogd']) {
    // er is niet ingelogd, geef een error
    returnError("Geen sessie (meer) actief");
}

// test of de verplichte data is ingevuld
if (
    !isset($_POST['course']) ||
    !isset($_POST['veldType']) ||
    !isset($_POST['veld']) ||
    !isset($_POST['waarde'])
) {
    // niet alle verplichte waardes zijn opgegeven, geef een error
    returnError("Niet alle verplichte data is ingevuld");
} else {
    // de waardes zijn wel ingevuld, sla ze op in variabelen
    $course = $_POST['course'];
    $veldType = $_POST['veldType'];
    $veld = $_POST['veld'];
    $waarde = $_POST['waarde'];
}

// test of course een nummer is
if (!is_numeric($course)) {
    // course is geen nummer geef een error
    returnError("Course is geen nummer");
}

// als het veldtype een hoofdstuk of video veld is
if ($veldType === 'hoofdstuk' || $veldType === 'video') {
    // test of de hoofdstukdata is ingevuld
    if (!isset($_POST['hoofdstuk'])) {
        // de hoofdstukdata is niet ingevuld, geef een error
        returnError("Niet alle verplichte data is ingevuld");
    } else {
        // de hoofdstukdata is ingevuld, sla het op in een variabel
        $hoofdstuk = $_POST['hoofdstuk'];
    }
    // test of hoofdstuk een nummer is
    if (!is_numeric($hoofdstuk)) {
        // hoofdstuk is geen nummer geef een error
        returnError("Hoofdstuk is geen nummer");
    }
}

// als het veldtype een video veld is
if ($veldType === 'video') {
    // test of de videodata is ingevuld
    if (!isset($_POST['video'])) {
        // de videodata is niet ingevuld, geef een error
        returnError("Niet alle verplichte data is ingevuld");
    } else {
        // de videodata is ingevuld, sla het op in een variabel
        $video = $_POST['video'];
    }
    // test of video een nummer is
    if (!is_numeric($video)) {
        // video is geen nummer geef een error
        returnError("video is geen nummer");
    }
}

// test of het veldtype een toegestaane waarde is
if (!isset($veldTypeNaarTabel[$veldType])) {
    // het veldtype is geen toegestaane waarde, geeef een error
    returnError("Veldtype is geen toegestaane waarde");
}
// test of het veld een toegestaande waarde is
if (!isset($veldNaarKolom[$veld])) {
    // het veldtype is geen toegestaane waarde, geeef een error
    returnError("Veld is geen toegestaane waarde");
}

// test of het veld bij het veldtype hoort
if ($veldType === 'course') {   // course
    // toegestaande waardes: course-titel, course-zichtbaarheid, course-omschrijving
    if (
        $veld !== 'course-titel' &&
        $veld !== 'course-zichtbaarheid' &&
        $veld !== 'course-omschrijving'
    ) {
        // foute waarde, geef een error
        returnError("Het veld is geen toegestaan veld voor dit veldtype");
    }
}
if ($veldType === 'hoofdstuk') {    // hoofdstuk
    // toegestaande waardes: hoofdstuk-titel
    if (
        $veld !== 'hoofdstuk-titel'
    ) {
        // foute waarde, geef een error
        returnError("Het veld is geen toegestaan veld voor dit veldtype");
    }
}
if ($veldType === 'video') {    // video
    // toegestaande waardes: video-titel, video-url, video-omschrijving
    if (
        $veld !== 'video-titel' &&
        $veld !== 'video-url' &&
        $veld !== 'video-omschrijving'
    ) {
        // foute waarde, geef een error
        returnError("Het veld is geen toegestaan veld voor dit veldtype");
    }
}

// escape html entities en sql injectie
$waarde = $mysqli->real_escape_string(htmlentities($waarde));

// * verwerk data
// query basis
$query = "UPDATE `$veldTypeNaarTabel[$veldType]` SET `$veldNaarKolom[$veld]` = '$waarde' WHERE ";

// voeg de where clause aan de query toe gebasseerd op het veldtype
if ($veldType === "course") {     // course
    $query .= "`id` = $course;";
}
if ($veldType === "hoofdstuk") {  // hoofdstuk
    $query .= "`id` = $hoofdstuk AND `courseId` = $course;";
}
if ($veldType === "video") {      // video
    $query .= "`id` = $video AND `hoofdstukId` = $hoofdstuk AND `courseId` = $course;";
}

// voer de query uit en test of dit succesvol is gebeurt
if (!$mysqli->query($query)) {
    // er iets iets fout gegaan in de database, geef een error
    returnError("Databasefout");
} else {
    // de query is succesvol uitgevoerd return de succesdata
    echo json_encode($data);
}
