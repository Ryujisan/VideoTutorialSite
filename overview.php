<?php

session_start();
// require de config
require_once('config.inc.php');
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
    <link rel="stylesheet" href="css/overview.css">
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon-32x32.png">

    <!-- Overig -->
    <title>Course Overzicht</title>
</head>

<body>
    <!-- NAVIGATIEBALK -->
    <?php
    $currentPage = "overview";
    include('header.inc.php');
    ?>
    <div class="fotobox">
        <img class="fotoglr" src="img/glrLogo.png">
    </div>
    <div class="bovenvlak ">
        <h1 class="text_midden">WELKOM OP MIJN WEBSITE</h1>
    </div>
    <main class="container-fluid main-dinges">
        <br>
        <?php
        if (isset($_SESSION['ingelogd'])) {
            echo "<button type=\"button\" class=\"btn btn-success btn-block btn-lg\" data-toggle=\"modal\" data-target=\"#maakcourseinstantbutton\">toevoegen</button>";
        }
        ?>
        <div class=" ml-1 mr-1 row">
            <div class="col-12">
                <!-- INHOUD -->
                <?php
                $query = "SELECT `course`.`id`,`course`.`titel`, `course`.`omschrijving` FROM `course`";
                if (!isset($_SESSION['ingelogd'])) {
                    $query .=  " WHERE `visibility` = 'zichtbaar'";
                }
                $resultaat = mysqli_query($mysqli, $query);
                //4 controleer aantal rijen
                $aantal = mysqli_num_rows($resultaat);

                if ($aantal == 0) {
                    echo "Er zitten geen rijen in deze tabel";
                } else {
                    //er zitten wel rijen in
                    //loop door de rijen - maak tabel
                    while ($rij = mysqli_fetch_array($resultaat)) {

                        if (isset($_SESSION['ingelogd'])) {
                            echo '
                                <a class="text-decoration-none" href="course.php?id=' . $rij['id'] . '">
                                    <div class="row bg-course rounded mt-4 ">
                                        <div class="col-md-3 col-12 plaatje-container rounded">
                                            <div class="plaatje"></div>
                                        </div>
                                        <div class="col-md-9 col-12">
                                            <h1 class="h1_course mt-2"> ' . $rij['titel'] . ' </h1>
                                            <p class="info_course">' . $rij['omschrijving'] . '</p>
                                        </div>
                                    </div>
                                </a>
                                <div class="row"><a class="btn btn-block btn-info ml-auto col-md-9 col-12" href="course_bewerk.php?course=' . $rij['id'] . '">Bewerk</a></div>
                                ';
                        } else {
                            echo '
                                <a class="text-decoration-none" href="course.php?id=' . $rij['id'] . '">
                                    <div class="row bg-course rounded mt-4 ">
                                        <div class="col-md-3 col-12 plaatje-container rounded">
                                            <div class="plaatje"></div>
                                        </div>
                                        <div class="col-md-9 col-12">
                                            <h1 class="h1_course mt-2"> ' . $rij['titel'] . ' </h1> 
                                            <p class="info_course">' . $rij['omschrijving'] . '</p>
                                        </div>
                                    </div>
                                </a>
                                ';
                        }
                    }
                }
                ?>
            </div>
        </div>
    </main>

    <?php

    // test of er een sessie actief is
    if (isset($_SESSION['ingelogd'])) {

    ?>

        <div class="modal fade" id="maakcourseinstantbutton" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">

                    <form id="tutorialMaakModalForm" action="api/newcoursedata.php" method="post">


                        <div class="modal-body">
                            <div class="form-group">
                                <br>
                                <input name="tutorialInput" type="text" class="form-control my-2" id="tutorialInput" placeholder="Titel van de course"><br>
                                <input name="urlInput" type="text" class="form-control my-2" id="urlInput" placeholder="URL van de eerste video">
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <input name="submitFormButton" type="submit" value="Maak Course" class="btn btn-primary">
                        </div>
                    </form>

                </div>
            </div>
        </div>

    <?php
    }

    ?>

    <!-- SCRIPTS -->
    <!-- jQuery, then Popper.js en Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

    <script>
        function getYoutubeId(url) {
            var regExp = /^.*(youtu\.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
            var match = url.match(regExp);
            if (match && match[2].length == 11) {
                return match[2];
            } else {
                // error
                return false;
            }
        }

        $("#tutorialMaakModalForm").submit(function() {

            let url = $("#urlInput").val();
            if (getYoutubeId(url)) {

                return true;

            } else {

                alert('Fout URL!');
                return false;

            }

        });
    </script>

</body>

</html>