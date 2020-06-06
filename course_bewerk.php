<?php
// start de session
session_start();
// require de config
require_once('config.inc.php');

// test of de gebruiker is ingelogd
if (!isset($_SESSION['ingelogd'])) {
    // de gebruiker is niet ingelogd, stuur ze naar de homepage
    header("location: ./");
}

// check of het course id is aangegeven
if (!isset($_GET['course'])) {
    // er is geen course id gespecificeerd
    header("location: ./");
} else {
    // haal het course id op uit de url
    $courseID = $_GET['course'];
}

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
    <link rel="stylesheet" href="css/header.css">
    <style>
        #input-coursetitel {
            font-size: 1.75rem;
        }

        .verwijder-video {
            position: absolute;
            right: 0.5rem;
            top: 0;
            bottom: 0;
        }

        .verwijder-video:hover {
            color: var(--red);
        }

        .is-loading {
            opacity: 0.75 !important;
        }
    </style>

    <!-- Overig -->
    <title>Course Maker | Video Tutorial Website</title>
</head>

<body data-course-id="<?php echo $courseID ?>">
    <!-- NAVIGATIEBALK -->
    <?php
    $currentPage = "overview";
    include('header.inc.php');
    ?>

    <!-- INHOUD -->
    <div class="container pt-4">
        <div class="row">

            <div class="col-12">

                <!-- Course Info -->
                <div id="mainCourseInfo">
                    <h1>Bewerk Course</h1>
                    <div class="form-row">
                        <div class="form-group col-md-8 position-relative">
                            <input id="input-coursetitel" class="form-control form-control-lg h-100" type="text" placeholder="Course Titel" data-inputdata="course-titel" required>
                            <div class="invalid-tooltip"></div>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="input-coursezichtbaarheid" class="mb-0 ml-1">Zichtbaarheid</label>
                            <select id="input-coursezichtbaarheid" class="form-control" data-inputdata="course-zichtbaarheid">
                                <option value="verborgen">Verborgen</option>
                                <option value="zichtbaar">Zichtbaar</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input-courseomschrijving" class="mb-0 ml-1">Omschrijving</label>
                        <textarea id="input-courseomschrijving" class="form-control" placeholder="Lorem, ipsum dolor..." data-inputdata="course-omschrijving"></textarea>
                    </div>
                </div>

                <div class="mb-0">
                    <h2>Inhoud</h2>
                    <hr class="m-0" />
                </div>

                <!-- Cource Contents -->
                <div id="course-content">
                    Inhoud Laden...
                </div>

                <button type="button" class="btn btn-success btn-lg btn-block my-2 bg-vtsdarkgreen shadow-sm" buttonType="addHoofdstuk">Hoofdstuk Toevoegen</button>
                <a role="button" class="btn btn-info btn-lg btn-block my-2 shadow-sm" href="course.php?id=<?php echo $courseID ?>">Course Bekijken</a>
            </div>

        </div>
    </div>

    <!-- SCRIPTS -->
    <!-- jQuery, then Popper.js en Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <!-- video select script -->
    <script src="js/course_bewerk.js"></script>
</body>

</html>