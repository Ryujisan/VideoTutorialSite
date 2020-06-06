<?php
// config
require "../config.inc.php";

// maak variabelen aan
$data = [];

// test of alle verplichte data is opgegeven
if (!isset($_POST['course']) || 
    !isset($_POST['hoofdstuk']) ||
    !isset($_POST['video'])) {
    // niet alle verplichte data is opgegeven:

    // zet de error data
    $data['success'] = false;
    $data['error'] = "niet alle verplichte waardes zijn opgegeven. (course, hoofdstuk, video)";

    // zet de data op de pagina
    echo json_encode($data);

    // stop met het laden van de pagina
    die();
}

// haal de data op
$courseID = $_POST['course'];
$hoofdstukID = $_POST['hoofdstuk'];
$videoID = $_POST['video'];

// query
$query = "
    SELECT
        `hoofdstuk_video`.`titel`,
        `hoofdstuk_video`.`omschrijving`,
        `hoofdstuk_video`.`url`
    FROM `hoofdstuk_video`
    WHERE
        `hoofdstuk_video`.`courseId` = $courseID AND
        `hoofdstuk_video`.`hoofdstukId` = $hoofdstukID AND
        `hoofdstuk_video`.`id` = $videoID
";

// voel de query uit en haal de gegevens op
$resultaat = mysqli_query($mysqli, $query);

// check of de query successvol uit is gevoerd
if (!$resultaat) {
    // de query is niet succesvol uitgevoerd:

    // zet de error data
    $data['success'] = false;
    $data['error'] = "database error";

    // zet de data op de pagina
    echo json_encode($data);

    // stop met het laden van de pagina
    die();
}

// test of er een video terug is gekomen
if (!mysqli_num_rows($resultaat) > 0) {
    // er is geen video terug gestuurd: 

    // zet de error data
    $data['success'] = false;
    $data['error'] = "video bestaat niet in database";

    // zet de data op de pagina
    echo json_encode($data);

    // stop met het laden van de pagina
    die();
}

// alle checks zijn succesvol:

// haal de videodata uit het resultaat
$video = mysqli_fetch_array($resultaat);

// zet de benodigde data in het videoData array
$videoData['titel'] = $video['titel'];
$videoData['omschrijving'] = $video['omschrijving'];
$videoData['url'] = $video['url'];

// zet de data in het data array
$data['success'] = true;
$data['data'] = $videoData;

// zet de data op de pagina
echo json_encode($data);