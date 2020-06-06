<?php

// Gegevens
$dbserver = 'localhost';
$dbusername = 'project1167';
$dbpassword = '#3Geheim';
$dbdatabase = 'project1167_vtw';

// Database Connectie
$mysqli = mysqli_connect($dbserver, $dbusername, $dbpassword, $dbdatabase);

// Foutmelding als er een error is
if (!$mysqli) {
    echo "Error: Unable to connect to MySQL." . "\r\n";
    echo "Debugging errno: " . mysqli_connect_errno() . "\r\n";
    echo "Debugging error: " . mysqli_connect_error() . "\r\n";
    exit;
}

// Success bericht als de connectie succesvol is
// echo "Success: A proper connection to MySQL was made! The my_db database is great." . "\r\n";
// echo "Host information: " . mysqli_get_host_info($mysqli) . "\r\n";