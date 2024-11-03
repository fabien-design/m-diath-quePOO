<?php 

use App\Enum\GenreEnum;
use App\Router\Router;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
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
        <h1 class="text-6xl font-bold py-4" >Edit Book</h1>
        <form action="/<?= Router::use('updateBooks', $book->getId()) ?>" method="post">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($book->getTitle()); ?>" required><br>
            <label for="author">Author:</label>
            <input type="text" id="author" name="author" value="<?= htmlspecialchars($book->getAuthor()); ?>" required><br>
            <label for="pageNumber">Number of pages:</label>
            <input type="number" id="pageNumber" name="pageNumber" value="<?= htmlspecialchars($book->getPageNumber()); ?>" required><br>
            <label for="available">Available:</label>
            <input type="checkbox" id="available" name="available" <?php echo $book->getAvailable() ? 'checked' : ''; ?>><br>
                
            <input type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800" value="Update Book">
        </form>
    </main>
</body>
</html>