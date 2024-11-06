<?php

namespace App\Controller;

use App\Database\Database;
use App\Enum\GenreEnum;
use App\Model\Book;
use App\Model\Movie;
use App\Router\Router;

final readonly class BookController
{

    public function index() : void
    {
        $books = Book::getBooks();
        
        include "../src/view/books.php";
    }

    public function show(int $id) : void
    {
        $book = Book::getBookById($id);
        
        include "../src/view/books_show.php";
    }

    public function create() : void
    {
        if (isset($_SESSION['user']) && !in_array('user', $_SESSION['user']['roles'])) {
            return;
        }

        if (empty($_POST['title']) || empty($_POST['author']) || empty($_POST['pageNumber'])) {
            return;
        }

        $title = $_POST['title'];
        $author = $_POST['author'];
        $pageNumber = $_POST['pageNumber'];
        $available = isset($_POST['available']) ? true : false;

        if (!is_numeric($pageNumber) || $pageNumber <= 0) {
            return;
        }

        $book = new Book(null, $title, $author, $available, $pageNumber);
        $book->persist();
        Router::redirect("books");
    }

    public function edit(int $id) : void
    {
        if (isset($_SESSION['user']) && !in_array('user', $_SESSION['user']['roles'])) {
            return;
        }

        $book = Book::getBookById($id);
        
        include "../src/view/books_edit.php";
    }

    public function update(int $id) : void
    {
        if (isset($_SESSION['user']) && !in_array('user', $_SESSION['user']['roles'])) {
            return;
        }

        $title = $_POST['title'];
        $author = $_POST['author'];
        $pageNumber = $_POST['pageNumber']; 
        $available = $_POST['available'];

        $book = Book::getBookById($id);
        $book->setTitle($title);
        $book->setAuthor($author);
        $book->setPageNumber($pageNumber);
        $book->setAvailable($available);
        $book->save();
        Router::redirect("showBooks",$id);
    }

    public function delete(int $id) : void
    {
        if (isset($_SESSION['user']) && !in_array('user', $_SESSION['user']['roles'])) {
            return;
        }

        $book = Book::getBookById($id);
        $book->delete();
        Router::redirect("books");
    }

    public function borrow(int $id) : void
    {
        if (isset($_SESSION['user']) && !in_array('user', $_SESSION['user']['roles'])) {
            return;
        }

        $book = Book::getBookById($id);
        $book->setAvailable(false);
        $book->save();
        Router::redirect("showBooks",$id);
    }

    public function return(int $id) : void
    {
        if (isset($_SESSION['user']) && !in_array('user', $_SESSION['user']['roles'])) {
            return;
        }

        $book = Book::getBookById($id);
        $book->setAvailable(true);
        $book->save();
        Router::redirect("showBooks",$id);
    }

}

