<?php
// config
require "../config.inc.php";

// maak variabelen aan
$data = [];

// test of het course id is opgegeven
if (!isset($_POST['course'])) {
    // niet alle verplichte data is opgegeven:

    // zet de error data
    $data['success'] = false;
    $data['error'] = "geen courseID opgegeven";

    // zet de data op de pagina
    echo json_encode($data);

    // stop met het laden van de pagina
    die();
}

// haal het course id op
$courseID = $_POST['course'];

// query
$query = "
    SELECT 
        `titel`,
        `omschrijving`,
        `visibility`
    FROM `course`
    WHERE `id` = $courseID;
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

// test of er een course terug is gekomen
if (!mysqli_num_rows($resultaat) > 0) {
    // er is geen course terug gestuurd: 

    // zet de error data
    $data['success'] = false;
    $data['error'] = "course bestaat niet in database";

    // zet de data op de pagina
    echo json_encode($data);

    // stop met het laden van de pagina
    die();
}

// alle checks zijn succesvol:

// haal de coursedata uit het resultaat
$course = mysqli_fetch_array($resultaat);

// zet de benodigde data in het courseData array
$courseData['titel'] = $course['titel'];
$courseData['omschrijving'] = $course['omschrijving'];
$courseData['zichtbaarheid'] = $course['visibility'];

// zet de data in het data array
$data['success'] = true;
$data['data'] = $courseData;

// zet de data op de pagina
echo json_encode($data);
