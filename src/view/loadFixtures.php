<?php

use App\Database\Database;
use App\Model\Album;
use App\Enum\GenreEnum;
use App\Model\Book;
use App\Model\Movie;
use App\Model\User;
use App\Router\Route;
use App\Router\Router;

$users = User::generateFixtures();
$albums = Album::generateFixtures();
$books = Book::generateFixtures();
$movies = Movie::generateFixtures();

$db = new Database();
$connexion = $db->connect();

// create tables if not exist
$stmt = $connexion->prepare("CREATE TABLE IF NOT EXISTS user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    roles JSON NOT NULL
)");
$stmt->execute();

$stmt = $connexion->prepare("CREATE TABLE IF NOT EXISTS album (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    available BOOLEAN NOT NULL,
    songNumber INT NOT NULL,
    editor VARCHAR(255) NULL
)");
$stmt->execute();
$stmt = $connexion->prepare("CREATE TABLE IF NOT EXISTS book (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    available BOOLEAN NOT NULL,
    pageNumber INT NOT NULL
)");
$stmt->execute();
$stmt = $connexion->prepare("CREATE TABLE IF NOT EXISTS movie (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    available FLOAT NOT NULL,
    duration INT NOT NULL,
    genre ENUM('ACTION', 'COMEDY', 'DRAMA', 'FANTASY', 'HORROR', 'MYSTERY', 'ROMANCE', 'THRILLER', 'WESTERN', 'SCIENCE_FICTION') NOT NULL
)");
$stmt->execute();

foreach ($users as $user) {
    $username = $user->getUsername();
    $email = $user->getEmail();
    $password = $user->getPassword();
    $roles = json_encode($user->getRoles());
    $stmt = $connexion->prepare("INSERT INTO user (username, email, password, roles) VALUES (:username, :email, :password, :roles)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':roles', $roles);
    if(!$stmt->execute()) {
        throw new Exception("Error while inserting User");
    }
} 

foreach ($albums as $album) {
    $title = $album->getTitle();
    $author = $album->getAuthor();
    $available = $album->getAvailable();
    $songNumber = $album->getSongNumber();
    $editor = $album->getEditor();
    $stmt = $connexion->prepare("INSERT INTO album (title, author, available, songNumber, editor) VALUES (:title, :author, :available, :songNumber, :editor)");
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':author', $author);
    $stmt->bindParam(':available', $available);
    $stmt->bindParam(':songNumber', $songNumber);
    $stmt->bindParam(':editor', $editor);
    if(!$stmt->execute()) {
        throw new Exception("Error while inserting Album");
    }
}

foreach ($books as $book) {
    $title = $book->getTitle();
    $author = $book->getAuthor();
    $available = $book->getAvailable();
    $nbPages = $book->getPageNumber();
    $stmt = $connexion->prepare("INSERT INTO book (title, author, available, pageNumber) VALUES (:title, :author, :available, :nbPages)");
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':author', $author);
    $stmt->bindParam(':available', $available);
    $stmt->bindParam(':nbPages', $nbPages);
    if(!$stmt->execute()) {
        throw new Exception("Error while inserting Book");
    }
}

foreach ($movies as $movie) {
    $title = $movie->getTitle();
    $author = $movie->getAuthor();
    $available = $movie->getAvailable();
    $duration = $movie->getDuration();
    $genre = $movie->getGenre();
    $stmt = $connexion->prepare("INSERT INTO movie (title, author, available, duration, genre) VALUES (:title, :author, :available, :duration, :genre)");
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':author', $author);
    $stmt->bindParam(':available', $available);
    $stmt->bindParam(':duration', $duration);
    $stmt->bindParam(':genre', $genre);
    if(!$stmt->execute()) {
        throw new Exception("Error while inserting Movie");
    }
}

Router::redirect("welcome");
