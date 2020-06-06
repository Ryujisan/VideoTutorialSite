<?php
// start de sessie
session_start();
// require de config
require_once('config.inc.php');

// haal het video id uit het url
$courseID = $_GET["id"];

// haal de gegevens van de course uit de database

// query met de gegevens van de course
$courseQuery = "
    SELECT 
        `course`.`titel`,
        `course`.`visibility`
    FROM `course`
    WHERE `course`.`id` = $courseID;";

// voer de query uit
$courseQueryResultaat = mysqli_query($mysqli, $courseQuery);
if (!$courseQueryResultaat) {
    // als de database query niet succesvol is uitgevoerd
    header("location:overview.php");
    die();
}
if (!mysqli_num_rows($courseQueryResultaat) > 0) {
    // als de query geen data terug stuurt
    header("location:overview.php");
    die();
}

// sla de gegevens op een een array
$course = mysqli_fetch_array($courseQueryResultaat);

// haal de hoofdstuk met video's gegevens uit de database
$hoofdstukQuery = "
    SELECT
        DISTINCT `course_hoofdstuk`.`id`,
        `course_hoofdstuk`.`titel`
    FROM `course_hoofdstuk`
    LEFT JOIN `hoofdstuk_video` ON `course_hoofdstuk`.`id` = `hoofdstuk_video`.`hoofdstukId` AND hoofdstuk_video.courseId = course_hoofdstuk.courseId
    WHERE `course_hoofdstuk`.`courseId` = $courseID AND hoofdstuk_video.id IS NOT NULL;";
$hoofdstukken = mysqli_query($mysqli, $hoofdstukQuery);

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
    <link rel="stylesheet" href="css/course.css">

    <!-- Overig -->
    <title><?php echo $course["titel"] ?> | Video Tutorial Website</title>
</head>

<body class="d-flex flex-column" data-course-id="<?php echo $courseID ?>">
    <!-- NAVIGATIEBALK -->
    <?php
    $currentPage = "overview";
    include('header.inc.php');
    ?>

    <!-- INHOUD -->
    <main class="container-fluid flex-grow-1 d-flex flex-column">
        <div class="row bg-light flex-grow-1">

            <!-- content -->
            <div class="col-lg-9 col-xl-8 py-4 px-0">

                <!-- title -->
                <h1 id="course-title" class="mb-1 px-3"><?php echo $course["titel"] ?></h1>
                <h2 id="course-currentvideo-title" class="m-0 px-3"></h2>

                <!-- video -->
                <div class="m-3">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe id="course-currentvideo" src="" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                </div>

                <!-- info -->
                <ul class="nav nav-tabs" id="infoNavigatie" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="omschrijving-tab" data-toggle="tab" href="#omschrijving" role="tab" aria-controls="home" aria-selected="true">Omscrijving</a>
                    </li>
                    <?php
                    // test of er een sessie actief is
                    if (isset($_SESSION['ingelogd'])) {
                    ?>
                        <li class="nav-item">
                            <a class="nav-link text-success" href="course_bewerk.php?course=<?php echo $courseID ?>">Bewerk</a>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link text-danger" id="course-delete">Delete</but>
                        </li>
                        <form action="api/removecourse.php" method="post" id="deletecourseform">
                            <input type="hidden" name="course" value="<?php echo $courseID ?>">
                        </form>
                    <?php
                    }
                    ?>
                </ul>
                <div class="tab-content border-bottom" id="infoContent">
                    <div class="tab-pane fade show active p-3 bg-white" id="omschrijving" role="tabpanel" aria-labelledby="omschrijving-tab">
                    </div>
                </div>

            </div>

            <!-- sidebar -->
            <div class="col-lg-3 col-xl-4 px-0 border-left bg-white">

                <!-- sidebar acordion -->
                <div id="course-navigatie" class="acordion sticky-top">

                    <?php
                    // loop door alle hoofdstukken heen en geef deze allemaal een dropdown menu

                    while ($hoofdstuk = mysqli_fetch_array($hoofdstukken)) {
                        // sla het hoofdstukID op in een variabel
                        $hoofdstukID = $hoofdstuk["id"];

                        // haal alle video's uit de database voor dit hoofdstuk
                        $videoQuery = "
                        SELECT
                            `hoofdstuk_video`.`id`,
                            `hoofdstuk_video`.`titel`
                        FROM `hoofdstuk_video`
                        WHERE
                            `hoofdstuk_video`.`courseId` = $courseID AND
                            `hoofdstuk_video`.`hoofdstukId` = $hoofdstukID;";
                        $videos = mysqli_query($mysqli, $videoQuery);

                    ?>
                        <!-- hoofdstuk <?php echo $hoofdstukID ?> -->
                        <button id="headingHoofdstuk<?php echo $hoofdstukID ?>" class="btn btn-block btn-lg btn-light border collapsed" type="button" data-toggle="collapse" data-target="#hoofdstuk<?php echo $hoofdstukID ?>" aria-expanded="false" aria-controls="hoofdstuk<?php echo $hoofdstukID ?>"><?php echo $hoofdstuk["titel"] ?></button>

                        <div id="hoofdstuk<?php echo $hoofdstukID ?>" class="collapse" aria-labelledby="headingHoofdstuk<?php echo $hoofdstukID ?>" data-parent="#course-navigatie" data-hoofdstuk-num="<?php echo $hoofdstukID ?>">
                            <div class="list-group">

                                <?php
                                // loop door alle videos van dit hoofdstuk heen en voeg ze toe aan het dropdown menu

                                while ($video = mysqli_fetch_array($videos)) {

                                ?>
                                    <button type="button" class="list-group-item list-group-item-action video-select-btn" data-video-num="<?php echo $video["id"] ?>"><?php echo $video["titel"] ?></button>
                                <?php

                                }
                                ?>

                            </div>
                        </div>
                    <?php
                    }
                    ?>

                </div>

            </div>

        </div>
    </main>

    <!-- SCRIPTS -->
    <!-- jQuery, then Popper.js en Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <!-- video select script -->
    <script src="js/course_videoselect.js"></script>
    <?php
    if (isset($_SESSION['ingelogd'])) { ?>

        <script>
            $("#course-delete").click(function() {
                if (window.confirm("Weet je zeker dat je deze course wilt verwijderen?")) {
                    console.log("yay");
                    $("#deletecourseform").submit();
                } else {
                    console.log("nay");
                }
            });
        </script>

    <?php }
    ?>
</body>

</html>