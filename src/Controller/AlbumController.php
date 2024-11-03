<?php

namespace App\Controller;

use App\Model\Album;
use App\Router\Router;

final readonly class AlbumController
{

    public function index() : void
    {
        $albums = Album::getAlbums();
        
        include "../src/view/albums.php";
    }

    public function show(int $id) : void
    {
        $album = Album::getAlbumById($id);
        
        include "../src/view/albums_show.php";
    }

    public function create() : void
    {
        if (empty($_POST['title']) || empty($_POST['author']) || empty($_POST['songNumber']) || empty($_POST['editor'])) {
            return;
        }

        $title = $_POST['title'] ?? '';
        $author = $_POST['author'] ?? '';
        $songNumber = intval($_POST['songNumber']) ?? 0;
        $editor = $_POST['editor'] ?? '';
        $available = isset($_POST['available']) ? true : false;

        if(!is_numeric($songNumber) || $songNumber <= 0) {
            return;
        }

        $album = new Album(null, $title, $author, $available, $songNumber, $editor);
        $album->persist();
        Router::redirect("albums");
    }

    public function edit(int $id) : void
    {
        $album = Album::getAlbumById($id);
        
        include "../src/view/albums_edit.php";
    }

    public function update(int $id) : void
    {
        $title = $_POST['title'] ?? '';
        $author = $_POST['author'] ?? '';
        $songNumber = $_POST['songNumber'] ?? 0;
        $editor = $_POST['editor'] ?? '';
        $available = isset($_POST['available']) ? true : false;

        $album = Album::getAlbumById($id);
        $album->setTitle($title);
        $album->setAuthor($author);
        $album->setSongNumber($songNumber);
        $album->setEditor($editor);
        $album->setAvailable($available);
        $album->save();
        Router::redirect("showAlbums", $id);
    }

    public function delete(int $id) : void
    {
        $album = Album::getAlbumById($id);
        $album->delete();
        Router::redirect("albums");
    }

    public function borrow(int $id) : void
    {
        $album = Album::getAlbumById($id);
        $album->setAvailable(false);
        $album->save();
        Router::redirect("showAlbums",$id);
    }

    public function return(int $id) : void
    {
        $album = Album::getAlbumById($id);
        $album->setAvailable(true);
        $album->save();
        Router::redirect("showAlbums",$id);
    }

}

