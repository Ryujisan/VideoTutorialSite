<?php
// config
require "../config.inc.php";
session_start();

if (!$_SESSION['ingelogd']) {
    // er is niet ingelogd, geef een error
    echo "Geen sessie (meer) actief";
    die();
}

$tutorialInput;
$urlInput;

if (
    !isset($_POST['tutorialInput']) ||
    !isset($_POST['urlInput'])
) {
    // niet alle verplichte waardes zijn opgegeven, geef een error
    echo "Niet alle verplichte data is ingevuld";
    die();
} else {
    // de waardes zijn wel ingevuld, sla ze op in variabelen
    $tutorialInput = $_POST['tutorialInput'];
    $urlInput = $_POST['urlInput'];
}

// filter de youtube url
$regExp = '/^.*(youtu\.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/';
preg_match($regExp, $urlInput, $match);
if ($match && strlen($match[2]) == 11) {
    $urlInput = $match[2];
} else {
    // error
    echo "Fout in de verstuurde data";
    die();
}

// Html en Mysqli clean up voor input
$tutorialInput = htmlentities($tutorialInput);
$urlInput = htmlentities($urlInput);

$tutorialInput = mysqli_real_escape_string($mysqli, $tutorialInput);
$urlInput = mysqli_real_escape_string($mysqli, $urlInput);

//Start query
// Course
$courseQuery = "INSERT INTO `course` (`id`, `titel`, `omschrijving`, `visibility`, `aanmaakDatum`) 
                VALUES (NULL, '$tutorialInput', '', 'verborgen', CURRENT_DATE())";

if (!mysqli_query($mysqli, $courseQuery)) {
    echo "Whoops, de course is niet aangemaakt.<br>";
}


// Hoofdstuk & Course ID
$courseId = mysqli_insert_id($mysqli);

$hoofdstukQuery = "INSERT INTO `course_hoofdstuk` (`id`, `courseId`, `titel`) VALUES ('1', '$courseId', 'Nieuw Hoofdstuk')";

if (!mysqli_query($mysqli, $hoofdstukQuery)) {
    echo "Whoops, het hoofdstuk is niet aangemaakt.<br>";
}


// Video
$videoQuery = "INSERT INTO `hoofdstuk_video` (`id`, `courseId`, `hoofdstukId`, `titel`, `url`, `omschrijving`) 
                VALUES ('1', '$courseId', '1', 'Nieuwe Video', '$urlInput', '')";

if (!mysqli_query($mysqli, $videoQuery)) {
    echo "Whoops, de video is niet aangemaakt.<br>";
} else {
    header("location: ../course_bewerk.php?course=$courseId");
}
