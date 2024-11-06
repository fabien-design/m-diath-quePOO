<?php

namespace App\Controller;

use App\Database\Database;
use App\Enum\GenreEnum;
use App\Router\Router;
use App\Model\Movie;

final readonly class MovieController
{

    public function index() : void
    {
        $allMovies = Movie::getMovies();
        include "../src/view/movies.php";
    }

    public function show(int $id) : void
    {
        $movie = Movie::getMovieById($id);
        
        include "../src/view/movies_show.php";
    }

    public function create() : void
    {
        if (isset($_SESSION['user']) && !in_array('user', $_SESSION['user']['roles'])) {
            return;
        }

        if (empty($_POST['title']) || empty($_POST['author']) || empty($_POST['duration']) || empty($_POST['genre'])) {
            return;
        }

        $title = $_POST['title'];
        $author = $_POST['author'];
        $duration = $_POST['duration'];
        $genre = $_POST['genre'];
        $available = isset($_POST['available']) ? true : false;

        if (!is_numeric($duration) || $duration <= 0) {
            return;
        }

        try {
            $movie = new Movie(null, $title, $author, $available, $duration, GenreEnum::from($genre));
            $movie->persist();
            Router::redirect("movies");
        } catch (\Exception $e) {
            return;
        }
    }

    public function edit(int $id) : void
    {
        if (isset($_SESSION['user']) && !in_array('user', $_SESSION['user']['roles'])) {
            return;
        }

        $movie = Movie::getMovieById($id);
        
        include "../src/view/movies_edit.php";
    }

    public function update(int $id) : void
    {
        if (isset($_SESSION['user']) && !in_array('user', $_SESSION['user']['roles'])) {
            return;
        }

        $id = intval($id);
        $title = $_POST['title'];
        $author = $_POST['author'];
        $duration = $_POST['duration'];
        $genre = $_POST['genre'];

        $movie = Movie::getMovieById($id);
        $movie->setTitle($title);
        $movie->setAuthor($author);
        $movie->setDuration($duration);
        $movie->setGenre(GenreEnum::from($genre));
        $movie->setAvailable($_POST['available'] ? true : false);
        $movie->save();
        Router::redirect("showMovies",$id);
    }

    public function delete(int $id) : void
    {
        if (isset($_SESSION['user']) && !in_array('user', $_SESSION['user']['roles'])) {
            return;
        }

        $id = intval($id);
        $movie = Movie::getMovieById($id);
        $movie->delete();
        Router::redirect("movies");
    }

    public function borrow(int $id) : void
    {
        $movie = Movie::getMovieById($id);
        $movie->setAvailable(false);
        $movie->save();
        Router::redirect("showMovies",$id);
    }

    public function return(int $id) : void
    {
        if (isset($_SESSION['user']) && !in_array('user', $_SESSION['user']['roles'])) {
            return;
        }

        $movie = Movie::getMovieById($id);
        $movie->setAvailable(true);
        $movie->save();
        Router::redirect("showMovies",$id);
    }

}

