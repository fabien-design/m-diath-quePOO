<?php

use App\Router\Router;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Details</title>
    <script type="module" src="scripts.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
</head>
<body>
    <?php 
        include 'includes/_header.php';
    ?>
    <main>
        <h1 class="text-6xl font-bold py-4"><?= $movie->getTitle() ?></h1>
        <p>Auteur : <?= $movie->getAuthor() ?></p>
        <p>Genre : <?= $movie->getGenre() ?></p>
        <p>Durée en minutes : <?= $movie->getDuration() ?></p>
        <p>Status : <?= $movie->getAvailable() ? 'Disponible' : 'Non disponible' ?></p>
        <!-- borrow the movie  -->
        <?php 
        //  only authenticated users 
        if (isset($_SESSION['user']) && in_array('user', $_SESSION['user']['roles'])) {
        ?>
        <form action="/<?= $movie->getAvailable() ? Router::use("borrowMovies", $movie->getId()) : Router::use("returnMovies", $movie->getId()) ?>" method="post">
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800" ><?= $movie->getAvailable() ? 'Emprunter' : 'Rendre' ?></button>
        </form>
        <?php
        // afficher modifier et supprimer pour users authentifier
        if (isset($_SESSION['user']) && in_array('user', $_SESSION['user']['roles'])) { ?>
            <a href="/<?= Router::use('editMovies', $movie->getId()) ?>" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800" >Modifier</a>
            <a href="/<?= Router::use('deleteMovies', $movie->getId()) ?>" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900" >Supprimer</a>
        <?php }
        } ?>
    </main>
</body>
</html>
