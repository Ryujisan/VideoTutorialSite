<?php
// config
require "../config.inc.php";
session_start();

if (!$_SESSION['ingelogd']) {
    // er is niet ingelogd, geef een error
    echo "Geen sessie (meer) actief";
    die();
}

// variables
$courseId;

if (
    !isset($_POST['course'])
) {
    // niet alle verplichte waardes zijn opgegeven, geef een error
    echo "Niet alle verplichte data is ingevuld";
    die();
} else {
    // de waardes zijn wel ingevuld, sla ze op in variabelen
    $courseId = $_POST['course'];
}

//Start query
$deleteQuery = "DELETE FROM `course` WHERE `course`.`id` = $courseId";
if (!mysqli_query($mysqli, $deleteQuery)) {
    echo "Fout bij het verwijderen van de course.<br>";
} else {
    header("Location: ../overview.php");
}
