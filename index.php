<?php
// start de sessie
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
	<link rel="stylesheet" href="css/index.css">

	<!-- Overig -->
	<title>HomePagina | Video Tutorial Website</title>
</head>

<body>
	<!-- NAVIGATIEBALK -->
	<?php
	$currentPage = "home";
	include('header.inc.php');
	?>

	<!-- INHOUD -->
	<main id="header-container" class="text-center text-light">

		<h1 id="header-title" class="mb-4">Welkom</h1>

		<a class="btn btn-lg btn-success mb-4" href="overview.php">Bekijk de Courses</a>

		<div id="header-images" class="carousel slide carousel-fade" data-ride="carousel" data-keyboard="false" data-touch="false" data-pause="false">
			<div class="carousel-inner">
				<div class="carousel-item active">
					<img src="https://picsum.photos/1920/1080?random=1" class="d-block w-100">
				</div>
				<div class="carousel-item">
					<img src="https://picsum.photos/1920/1080?random=2" class="d-block w-100">
				</div>
				<div class="carousel-item">
					<img src="https://picsum.photos/1920/1080?random=3" class="d-block w-100">
				</div>
			</div>
		</div>

	</main>

	<!-- SCRIPTS -->
	<!-- jQuery, then Popper.js en Bootstrap JS -->
	<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>

</html>