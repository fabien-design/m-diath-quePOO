<?php 

namespace App\Model;

use App\Database\Database;
use App\Enum\GenreEnum;

class Movie extends Media {
    public function __construct(?string $id, string $title, string $author, bool $available, private float $duration, private GenreEnum $genre) {
        parent::__construct($id, $title, $author, $available);
    }

    /**
     * Get the value of duration
     */ 
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set the value of duration
     *
     * @return  self
     */ 
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get the value of genre
     */ 
    public function getGenre()
    {
        return $this->genre->value;
    }

    /**
     * Set the value of genre
     *
     * @return  self
     */ 
    public function setGenre($genre)
    {
        $this->genre = $genre;

        return $this;
    }

    public static function generateFixtures() {
        $movies = [
            new Movie(null, "The Dark Knight", "Christopher Nolan", true, 152, GenreEnum::ACTION),
            new Movie(null, "Inception", "Christopher Nolan", true, 148, GenreEnum::ACTION),
            new Movie(null, "Interstellar", "Christopher Nolan", true, 169, GenreEnum::SCIENCE_FICTION),
            new Movie(null, "The Prestige", "Christopher Nolan", true, 130, GenreEnum::THRILLER),
            new Movie(null, "Dunkirk", "Christopher Nolan", true, 107, GenreEnum::ACTION),
            new Movie(null, "Memento", "Christopher Nolan", true, 113, GenreEnum::THRILLER),
            new Movie(null, "Tenet", "Christopher Nolan", true, 150, GenreEnum::ACTION),
            new Movie(null, "The Shawshank Redemption", "Frank Darabont", true, 142, GenreEnum::DRAMA),
            new Movie(null, "The Green Mile", "Frank Darabont", true, 189, GenreEnum::DRAMA),
            new Movie(null, "The Mist", "Frank Darabont", true, 126, GenreEnum::HORROR),
            new Movie(null, "The Majestic", "Frank Darabont", true, 152, GenreEnum::DRAMA),
            new Movie(null, "The Walking Dead", "Frank Darabont", true, 44, GenreEnum::HORROR),
            new Movie(null, "The Shawshank Redemption", "Frank Darabont", true, 142, GenreEnum::DRAMA),
            new Movie(null, "The Green Mile", "Frank Darabont", true, 189, GenreEnum::DRAMA),
            new Movie(null, "The Mist", "Frank Darabont", true, 126, GenreEnum::HORROR),
            new Movie(null, "The Majestic", "Frank Darabont", true, 152, GenreEnum::DRAMA),
            new Movie(null, "The Walking Dead", "Frank Darabont", true, 44, GenreEnum::HORROR),
            new Movie(null, "The Shawshank Redemption", "Frank Darabont", true, 142, GenreEnum::DRAMA),
            new Movie(null, "The Green Mile", "Frank Darabont", true, 189, GenreEnum::DRAMA),
            new Movie(null, "The Mist", "Frank Darabont", true, 126, GenreEnum::HORROR)
        ];

        return $movies;
    }

    public static function getMovies() {
        $db = new Database();
        $connexion = $db->connect();
        $stmt = $connexion->prepare("SELECT * FROM movie");
        $stmt->execute();
        $moviesDB = $stmt->fetchAll();

        $movies = [];
        foreach ($moviesDB as $movie) {
            $genre = GenreEnum::from($movie['genre']);
            $movieInst = new Movie($movie['id'], $movie['title'], $movie['author'], $movie['available'], $movie['duration'], $genre);
            array_push($movies, $movieInst);
        }
        return $movies;
    }

    public static function getMovieById(int $id) {
        $db = new Database();
        $connexion = $db->connect();
        $stmt = $connexion->prepare("SELECT * FROM movie WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $movie = $stmt->fetch();

        $genre = GenreEnum::from($movie['genre']);
        $movie = new Movie($movie['id'], $movie['title'], $movie['author'], $movie['available'], $movie['duration'], $genre);
        return $movie;
    }

    public function save() {
        $id = $this->getId();
        $available = $this->getAvailable();  
        $title = $this->getTitle();
        $author = $this->getAuthor();
        $duration = $this->getDuration();
        $genre = $this->getGenre();
        $db = new Database();
        $connexion = $db->connect();
        $stmt = $connexion->prepare("UPDATE movie SET title = :title, author = :author, available = :available, duration = :duration, genre = :genre WHERE id = :id");
        $stmt->bindParam(':available', $available, \PDO::PARAM_BOOL);
        $stmt->bindParam(':title', $title, \PDO::PARAM_STR);
        $stmt->bindParam(':author', $author, \PDO::PARAM_STR);
        $stmt->bindParam(':duration', $duration, \PDO::PARAM_INT);
        $stmt->bindParam(':genre', $genre);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount() === 0) {
            echo "Aucune ligne n'a été mise à jour. Vérifiez l'ID.";
        }
    }

    public function delete() {
        $id = $this->getId();
        $db = new Database();
        $connexion = $db->connect();
        $stmt = $connexion->prepare("DELETE FROM movie WHERE id = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();

        // Vérification de suppression
        if ($stmt->rowCount() === 0) {
            echo "Aucune ligne n'a été supprimée. Vérifiez l'ID.";
        }
    }

    public function persist() {
        $title = $this->getTitle();
        $author = $this->getAuthor();
        $available = $this->getAvailable();
        $duration = $this->getDuration();
        $genre = $this->getGenre();
        $db = new Database();
        $connexion = $db->connect();
        $stmt = $connexion->prepare("INSERT INTO movie (title, author, available, duration, genre) VALUES (:title, :author, :available, :duration, :genre)");
        $stmt->bindParam(':title', $title, \PDO::PARAM_STR);
        $stmt->bindParam(':author', $author, \PDO::PARAM_STR);
        $stmt->bindParam(':available', $available, \PDO::PARAM_BOOL);
        $stmt->bindParam(':duration', $duration, \PDO::PARAM_INT);
        $stmt->bindParam(':genre', $genre);
        $stmt->execute();
    }
    
}
