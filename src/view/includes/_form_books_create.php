<?php

use App\Router\Router;
?>
<form action="/<?= Router::use('createBooks') ?>" method="post">
    <label for="title">Title:</label>
    <input type="text" id="title" name="title" required><br>
    <label for="author">Author:</label>
    <input type="text" id="author" name="author" required><br>
    <label for="pageNumber">Number of pages:</label>
    <input type="number" id="pageNumber" name="pageNumber" required><br>
    <label for="available">Available:</label>
    <input type="checkbox" id="available" name="available"><br>
    
    <input type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800" value="Add Book">
</form>
