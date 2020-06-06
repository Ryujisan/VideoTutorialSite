<?php

/**
 * 
 * welke pagina actief is kun je bepalen met het $currentPage variabel
 * door deze een waarde te geven voordat je de page include kan je de actieve pagina in de navigatiebalk aanpassen
 * 
 * $currentPage waardes:
 *  home
 *  overview
 * 
 */
?>
<nav class="navbar navbar-expand-md navbar-light px-2 p-md-0  bg-vtslightgreen">
    <a class="navbar-brand ml-2 d-md-none text-dark" href="#">Video Tutorial Website</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse mt-2 mt-md-0" id="navbarNav">
        <ul class="navbar-nav w-100">
            <li class="nav-item px-5 py-0 bg-vtsgreen <?php if ($currentPage == "home") {
                                                            echo "active";
                                                        }; // $currentPage = "home" 
                                                        ?>">
                <a class="nav-link text-light" href="index.php">Home</a>
            </li>
            <li class="nav-item px-5 py-0 bg-vtsgreen <?php if ($currentPage == "overview") {
                                                            echo "active";
                                                        }; // $currentPage = "overview" 
                                                        ?>">
                <a class="nav-link text-light" href="overview.php">Courses</a>
            </li>
            <?php
            if (isset($_SESSION['ingelogd'])) { ?>
                <li class="nav-item px-5 py-0 ml-md-auto bg-vtsgreen">
                    <a class="nav-link text-light" href="uitlog.php">Log uit</a>
                </li>
            <?php }
            ?>
        </ul>
    </div>
</nav>