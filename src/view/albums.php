<?php

use App\Router\Router;
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
        <h1 class="text-6xl font-bold py-4">Les albums</h1>
        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <?php if (!empty($albums)): ?>
                            <th scope="col" class="px-6 py-4">Titre</th>
                            <th scope="col" class="px-6 py-4">Auteur</th>
                            <th scope="col" class="px-6 py-4">Disponible</th>
                            <th scope="col" class="px-6 py-4">Nombre de musiques</th>
                            <th scope="col" class="px-6 py-4">Editeur</th>
                            <th scope="col" class="px-6 py-4">Action</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($albums as $album): ?>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <?php echo htmlspecialchars($album->getTitle()); ?>
                            </th>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($album->getAuthor()); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($album->getAvailable() ? 'Oui' : 'Non'); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($album->getSongNumber()); ?></td>
                            <td class="px-6 py-4"><?php echo $album->getEditor() ? htmlspecialchars($album->getEditor()) : 'Aucun' ?></td>
                            <td class="px-6 py-4 flex">
                                <a href="/<?= Router::use("showAlbums", $album->getId()) ?>" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800" >Voir</a>
                                <?php
                                // afficher modifier et supprimer pour users authentifier
                                if (isset($_SESSION['user']) && in_array('user', $_SESSION['user']['roles'])) { ?>
                                    <a href="/<?= Router::use('editAlbums', $album->getId()) ?>" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800" >Modifier</a>
                                    <a href="/<?= Router::use('deleteAlbums', $album->getId()) ?>" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900" >Supprimer</a>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>

</html>