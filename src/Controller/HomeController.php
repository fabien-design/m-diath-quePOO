<?php

namespace App\Controller;

use App\Model\Book;
use App\Model\Album;
use App\Model\Movie;

final readonly class HomeController
{

    public function index() : void
    {
        $allMedias = [
            "books" => Book::getBooks(),
            "movies" => Movie::getMovies(),
            "albums" => Album::getAlbums()
        ];
        include "../src/view/welcome.php";
    }

    public function loadFixtures() : void 
    {
        
        include "../src/view/loadFixtures.php";
    }

    public function add() : void
    {
        include "../src/view/add.php";
    }

}
