<?php
// start de sessie
session_start();
// require de config
require_once("config.inc.php");

// test of de gebruiker niet al ingelogd is
if (isset($_SESSION['ingelogd'])) {
    // zo ja? redirect de user naar de index
    header("location:index.php");
}

// test of het form is verzonden
if (isset($_POST['submit'])) {
    // het formulier is verzonden verwerk het formulier:

    // zet het sucessvolgevalideerd variabel naar true
    $succesvolGevalideerd = true;

    // gegevens
    $gebruikersnaam = $_POST['gebruikersnaam'];
    $wachtwoord = $_POST['wachtwoord'];

    // filter dubbele whitespace
    $gebruikersnaam = preg_replace('/\s+/', ' ', $gebruikersnaam);
    $wachtwoord = preg_replace('/\s+/', ' ', $wachtwoord);

    // test of beide waardes zijn ingevuld (ALLE whitespace is weggefiltert met preg_replace)
    if (
        !strlen(preg_replace('/\s+/', '', $gebruikersnaam)) > 0 ||
        !strlen(preg_replace('/\s+/', '', $wachtwoord)) > 0
    ) {
        // zo niet? zet succesvolgevalideerd naar false
        $succesvolGevalideerd = false;
    }

    // filter de data met mysqli escape en htmlentities
    $gebruikersnaam = mysqli_escape_string($mysqli, htmlentities($gebruikersnaam));
    $wachtwoord = mysqli_escape_string($mysqli, htmlentities($wachtwoord));

    // encrypt het wachtwoord met md5
    $wachtwoord = md5($wachtwoord);

    // als de gegevens succesvol gevalideerd zijn:
    if ($succesvolGevalideerd) {
        // het formulier is succesvol gevalideerd, test of deze gebruiker bestaat in de database

        // maak de query
        $query = "SELECT `gebruiker`.`id`, `gebruiker`.`gebruikersnaam` FROM `gebruiker` WHERE `gebruiker`.`gebruikersnaam` = ? AND `gebruiker`.`wachtwoord` = ?";
        // prepare de query
        $stmt = mysqli_prepare($mysqli, $query);
        mysqli_stmt_bind_param($stmt, "ss", $gebruikersnaam, $wachtwoord);
        // execute de query en sla het resultaat op
        mysqli_stmt_execute($stmt);
        $resultaat = mysqli_stmt_get_result($stmt);

        // close de mysqli statement
        mysqli_stmt_close($stmt);

        // test of de gebruiker in de database bestaat
        if (mysqli_num_rows($resultaat) > 0) {
            // als de gebruiker bestaat:

            // zet het resultaat in een array;
            $user = mysqli_fetch_array($resultaat);

            // sla het id en de gebuikersnaam van de gebruiker op in de session
            $_SESSION['gebruikersid'] = $user['id'];
            $_SESSION['gebruikersnaam'] = $user['gebruikersnaam'];

            // zet het sessie variabel ingelogd naar true
            $_SESSION['ingelogd'] = true;

            // redirect naar de index
            header("location:index.php");
        } else {
            // als de gebruiker niet bestaat:

            // zet de inlog error in de sessie
            $_SESSION['inlog_error'] = "foute gebruikersnaam of wachtwoord";
            // zet de ingevulde gebruikersnaam in de sessie
            $_SESSION['inlog_gebruikersnaam'] = $gebruikersnaam;

            // redirect naar de inlog
            header("location:inlog.php");
        }
    } else {
        // het formulier is niet succesvol gevalideerd, laat een error zien

        // zet de inlog error in de sessie
        $_SESSION['inlog_error'] = "niet alle gegevens zijn ingevuld";
        // zet de ingevulde gebruikersnaam in de sessie
        $_SESSION['inlog_gebruikersnaam'] = $gebruikersnaam;

        // redirect naar de inlog
        header("location:inlog.php");
    }
} else {
    // Het formulier is niet verzonden, laat het formulier zien

?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <!-- Meta tags -->
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <!-- Favicon -->
        <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
        <link rel="manifest" href="/favicon/site.webmanifest">
        <link rel="mask-icon" href="/favicon/safari-pinned-tab.svg" color="#5bbad5">
        <link rel="shortcut icon" href="/favicon/favicon.ico">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="msapplication-config" content="/favicon/browserconfig.xml">
        <meta name="theme-color" content="#ffffff">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous" />
        <!-- Custom CSS -->
        <link rel="stylesheet" href="css/main.css">

        <!-- Overig -->
        <title>Log In | Video Tutorial Website</title>
    </head>

    <body class="bg-vtsgreen">
        <main class="container">
            <div class="row">
                <div class="col-12 col-md-8 mx-auto">
                    <form action="inlog.php" method="post" class="needs-validation my-5 p-3 bg-light rounded shadow" novalidate>
                        <h1>Log In</h1>
                        <div class="form-group">
                            <label for="input-GebruikersNaam">Gebruikersnaam:</label>
                            <input type="text" required name="gebruikersnaam" placeholder="bijv. BobDeBouwer" id="input-GebruikersNaam" class="form-control form-control-lg" <?php
                                                                                                                                                                                // zet de value van de input naar de gebruikersnaam als er unsuccesful is ingelogd
                                                                                                                                                                                if (isset($_SESSION['inlog_error'])) {
                                                                                                                                                                                    echo "value='" . $_SESSION['inlog_gebruikersnaam'] . "'";
                                                                                                                                                                                }
                                                                                                                                                                                ?>>
                            <div class="invalid-feedback">
                                Dit veld is verplicht!
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input-Wachtwoord">Wachtwoord:</label>
                            <input type="password" required name="wachtwoord" placeholder="bijv. SuperGeheimWachtwoord#1234" id="input-Wachtwoord" class="form-control form-control-lg">
                            <div class="invalid-feedback">
                                Dit veld is verplicht!
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="mb-3 text-danger text-uppercase">
                                <?php
                                // echo de inlog error als deze bestaat
                                if (isset($_SESSION['inlog_error'])) {
                                    echo $_SESSION['inlog_error'];
                                }

                                // clear de inlog gegevens van de session
                                unset($_SESSION['inlog_error']);
                                unset($_SESSION['inlog_gebruikersnaam']);
                                ?>
                            </div>
                            <input type="submit" name="submit" value="Log In" class="btn btn-block btn-lg btn-info">
                            <a class="d-block mx-auto my-3 text-center" href="index.php">Terug naar de home</a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
        <script>
            // Form Validatie Script
            (function() {
                'use strict';
                window.addEventListener('load', function() {
                    // Haal alle elementen op die validatie nodig hebben
                    var forms = document.getElementsByClassName('needs-validation');
                    // Loop ze door en vermijd het verzenden van het formulier
                    var validation = Array.prototype.filter.call(forms, function(form) {
                        form.addEventListener('submit', function(event) {
                            if (form.checkValidity() === false) {
                                event.preventDefault();
                                event.stopPropagation();
                            }
                            form.classList.add('was-validated');
                        }, false);
                    });
                }, false);
            })();
        </script>
    </body>

    </html>
<?php

}
