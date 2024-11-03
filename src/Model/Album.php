<?php 

namespace App\Model;

use App\Database\Database;
use Exception;

class Album extends Media {
    public function __construct(?string $id, string $title, string $author, bool $available, private int $songNumber, private ?string $editor) {
        parent::__construct($id, $title, $author, $available);
    }


    /**
     * Get the value of songNumber
     */ 
    public function getSongNumber()
    {
        return $this->songNumber;
    }

    /**
     * Set the value of songNumber
     *
     * @return  self
     */ 
    public function setSongNumber($songNumber)
    {
        $this->songNumber = $songNumber;

        return $this;
    }

    /**
     * Get the value of editor
     */ 
    public function getEditor()
    {
        return $this->editor;
    }

    /**
     * Set the value of editor
     *
     * @return  self
     */ 
    public function setEditor(?string $editor)
    {
        $this->editor = $editor;

        return $this;
    }

    public static function generateFixtures() {
        $albums = [];
        $albums[] = new Album(null, "Album 1", "Auteur 1", true, 10, "Editor 1");
        $albums[] = new Album(null, "Album 2", "Auteur 2", true, 12, "Editor 2");
        $albums[] = new Album(null, "Album 3", "Auteur 3", true, 15, null);
        $albums[] = new Album(null, "Thriller", "Michael Jackson", true, 9, "Epic");
        $albums[] = new Album(null, "Back in Black", "AC/DC", true, 10, "Atlantic");
        $albums[] = new Album(null, "The Dark Side of the Moon", "Pink Floyd", true, 10, "Harvest");
        $albums[] = new Album(null, "The Bodyguard", "Whitney Houston", true, 12, null);
        $albums[] = new Album(null, "Rumours", "Fleetwood Mac", true, 11, "Warner Bros");
        
        return $albums;
    }

    public static function getAlbums() {
        $db = new Database();
        $connexion = $db->connect();
        $stmt = $connexion->prepare("SELECT * FROM album");
        $stmt->execute();
        $albumsDB = $stmt->fetchAll();
        
        $albums = [];
        foreach ($albumsDB as $album) {
            $albumInst = new Album($album['id'], $album['title'], $album['author'], $album['available'], $album['songNumber'], $album['editor']);
            array_push($albums, $albumInst);
        }
        return $albums;
    }

    public static function getAlbumById(int $id) {
        $db = new Database();
        $connexion = $db->connect();
        $stmt = $connexion->prepare("SELECT * FROM album WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $album = $stmt->fetch();
        return new Album($album['id'], $album['title'], $album['author'], $album['available'], $album['songNumber'], $album['editor']);
    }

    public function save(): void {
        $id = $this->getId();
        $title = $this->getTitle();
        $author = $this->getAuthor();
        $available = $this->getAvailable();
        $songNumber = $this->getSongNumber();
        $editor = $this->getEditor();
        
        $db = new Database();
        $connection = $db->connect();
        $stmt = $connection->prepare("UPDATE album SET title = :title, author = :author, available = :available, songNumber = :songNumber, editor = :editor WHERE id = :id");
        $stmt->bindParam(':title', $title, \PDO::PARAM_STR);
        $stmt->bindParam(':author', $author, \PDO::PARAM_STR);
        $stmt->bindParam(':available', $available, \PDO::PARAM_BOOL);
        $stmt->bindParam(':songNumber', $songNumber, \PDO::PARAM_INT);
        $stmt->bindParam(':editor', $editor, \PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        
        // Vérification de mise à jour
        if ($stmt->rowCount() === 0) {
            echo "Aucune ligne n'a été mise à jour. Vérifiez l'ID.";
        }
    }

    public function delete(): void {
        $id = $this->getId();
        $db = new Database();
        $connection = $db->connect();
        $stmt = $connection->prepare("DELETE FROM album WHERE id = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        
        // Vérification de suppression
        if ($stmt->rowCount() === 0) {
            echo "Aucune ligne n'a été supprimée. Vérifiez l'ID.";
        }
    }

    public function persist(): void {
        $title = $this->getTitle();
        $author = $this->getAuthor();
        $available = $this->getAvailable();
        $songNumber = $this->getSongNumber();
        $editor = $this->getEditor();
        
        $db = new Database();
        $connection = $db->connect();
        $stmt = $connection->prepare("INSERT INTO album (title, author, available, songNumber, editor) VALUES (:title, :author, :available, :songNumber, :editor)");
        $stmt->bindParam(':title', $title, \PDO::PARAM_STR);
        $stmt->bindParam(':author', $author, \PDO::PARAM_STR);
        $stmt->bindParam(':available', $available, \PDO::PARAM_BOOL);
        $stmt->bindParam(':songNumber', $songNumber, \PDO::PARAM_INT);
        $stmt->bindParam(':editor', $editor, \PDO::PARAM_STR);
        $stmt->execute();
    }

}
