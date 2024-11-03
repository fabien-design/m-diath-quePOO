<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Media</title>
    <script type="module" src="scripts.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
    <script>
        function showForm() {
            var mediaType = document.getElementById("mediaType").value;
            var forms = document.getElementsByClassName("media-form");
            for (var i = 0; i < forms.length; i++) {
                forms[i].style.display = "none";
            }
            if (mediaType) {
                document.getElementById(mediaType + "-form").style.display = "block";
            }
        }
    </script>
</head>
<body>
    <?php 
    include "includes/_header.php";
    ?>
    <main>

        <h1 class="text-6xl font-bold py-4" >Add Media</h1>
        <form>
            <label for="mediaType">Choose media type:</label>
            <select id="mediaType" name="mediaType" onchange="showForm()">
                <option value="">Select...</option>
                <option value="books">Book</option>
                <option value="movies">Movie</option>
                <option value="albums">Album</option>
            </select>
        </form>
    
        <div id="books-form" class="media-form" style="display:none;">
            <?php include 'includes/_form_books_create.php'; ?>
        </div>
        <div id="movies-form" class="media-form" style="display:none;">
            <?php include 'includes/_form_movies_create.php'; ?>
        </div>
        <div id="albums-form" class="media-form" style="display:none;">
            <?php include 'includes/_form_albums_create.php'; ?>
        </div>
    </main>
</body>
</html>