<?php
// require de config
require_once("../config.inc.php");

// returnError functie
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

// declareer het data array
$data = [];
// zet de succeswaarde naar true
$data['success'] = true;

// haal het id op
$courseId = $_POST['course'];

// query voor het ophalen van hoofdstukken
$hoofdstukQuery = "SELECT `id`, `titel` FROM `course_hoofdstuk` WHERE `courseid` = ?";
// open een prepared statement voor de hoofdstukken
$hoofdstukstmt = $mysqli->prepare($hoofdstukQuery);

// check of de database geen error geeft
if (!$hoofdstukstmt) {
    // de query kan niet succesvol uitgevoerd worden:
    returnError("database error");
}

// voer de query uit met het gegeven id
$hoofdstukstmt->bind_param("i", $courseId);
$hoofdstukkenstmtResult = $hoofdstukstmt->execute();

// check of het prepared statement succesvol is uitgevoerd
if (!$hoofdstukkenstmtResult) {
    // het prepared statement is niet succesvol uitgevoerd:
    returnError("server error");
}

// haal het resultaat van de query op
$hoofdstukken = $hoofdstukstmt->get_result();

// sluit de prepared statement van de hoofdstukken af
$hoofdstukstmt->close();

// query voor het ophalen van videos
$videoQuery = "SELECT `id`, `titel`, `url`, `omschrijving` FROM `hoofdstuk_video` WHERE `courseid` = ? AND `hoofdstukid` = ?;";
// open een prepared statement voor de videos
$videostmt = $mysqli->prepare($videoQuery);

// check of de database geen error geeft
if (!$videostmt) {
    // de query kan niet succesvol uitgevoerd worden:
    returnError("database error");
}

// voeg een leeg hoofdstukken array toe aan de data
$data["hoofdstukken"] = [];

// loop door alle hoofdstukken heen
while ($hoofdstuk = mysqli_fetch_array($hoofdstukken)) {
    // bewaar het hoofdstukid in een variabel
    $hoofdstukId = $hoofdstuk['id'];

    // zet de hoofdstukdata in eenn variabel
    $hoofdstukdata = array(
        "titel" => $hoofdstuk['titel'],
        "videos" => []
    );

    // haal de videos uit het hoofdstuk op met het prepared statement
    $videostmt->bind_param("ii", $courseId, $hoofdstukId);
    $videostmtResult = $videostmt->execute();

    // check of het prepared statement succesvol is uitgevoerd
    if (!$videostmtResult) {
        // het prepared statement is niet succesvol uitgevoerd:
        returnError("server error");
    }

    // haal het resultaat van de query op
    $videos = $videostmt->get_result();

    while ($video = mysqli_fetch_array($videos)) {
        // bewaar het videoid in een variabel
        $videoId = $video['id'];

        // zet de videodata in een array
        $videodata = array(
            "titel" => $video['titel'],
            "url" => $video['url'],
            "omschrijving" => $video['omschrijving']
        );

        // voeg de videodata toe aan het hoofdstuk
        $hoofdstukdata["videos"][$videoId] = $videodata;
    }

    // voeg elk hoofdstuk toe aan het array
    $data["hoofdstukken"][$hoofdstukId] = $hoofdstukdata;
}

// zet de data op de pagina
echo json_encode($data);
