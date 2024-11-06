<?php 

namespace App\Model;

use App\Database\Database;

class Book extends Media {
    public function __construct(?string $id, string $title, string $author, bool $available, private int $pageNumber) {
        parent::__construct($id, $title, $author, $available);
    }

    public function getPageNumber() {
        return $this->pageNumber;
    }

    public function setPageNumber($pageNumber) {
        $this->pageNumber = $pageNumber;
        return $this;
    }

    /**
     * Generate fixtures
     * 
     * @return Book[]
     */
    public static function generateFixtures() {
        $books = [];
        $books[] = new Book(null,"Harry Potter", "J.K. Rowling", true, 300);
        $books[] = new Book(null,"Le Seigneur des Anneaux", "J.R.R. Tolkien", true, 500);
        $books[] = new Book(null,"Les Misérables", "Victor Hugo", true, 1500);
        $books[] = new Book(null,"Le Petit Prince", "Antoine de Saint-Exupéry", true, 100);
        $books[] = new Book(null,"1984", "George Orwell", true, 200);
        $books[] = new Book(null,"Le Rouge et le Noir", "Stendhal", true, 400);
        $books[] = new Book(null,"Les Fleurs du Mal", "Charles Baudelaire", true, 300);
        $books[] = new Book(null,"Les Trois Mousquetaires", "Alexandre Dumas", true, 600);
        $books[] = new Book(null,"Voyage au bout de la nuit", "Louis-Ferdinand Céline", true, 500);
        $books[] = new Book(null,"L'Étranger", "Albert Camus", true, 200);
        return $books;
    }

    /**
     * Get all books
     * 
     * @return Book[]
     */
    public static function getBooks() {
        $db = new Database();
        $connection = $db->connect();
        $stmt = $connection->prepare("SELECT * FROM book");
        $stmt->execute();
        $booksDB = $stmt->fetchAll();
        
        $books = [];
        foreach ($booksDB as $book) {
            $bookInst = new Book($book['id'], $book['title'], $book['author'], $book['available'], $book['pageNumber']);
            array_push($books, $bookInst);
        }
        return $books;
    }

    /**
     * Get a book by its ID
     * 
     * @param int $id
     * @return Book
     */
    public static function getBookById(int $id) {
        $db = new Database();
        $connection = $db->connect();
        $stmt = $connection->prepare("SELECT * FROM book WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $book = $stmt->fetch();
        return new Book($book['id'], $book['title'], $book['author'], $book['available'], $book['pageNumber']);
    }

    /**
     * Update a book
     */
    public function save(): void {
        $id = $this->getId();
        $title = $this->getTitle();
        $author = $this->getAuthor();
        $available = $this->getAvailable();
        $pageNumber = $this->getPageNumber();
        
        $db = new Database();
        $connection = $db->connect();
        $stmt = $connection->prepare("UPDATE book SET title = :title, author = :author, available = :available, pageNumber = :pageNumber WHERE id = :id");
        $stmt->bindParam(':title', $title, \PDO::PARAM_STR);
        $stmt->bindParam(':author', $author, \PDO::PARAM_STR);
        $stmt->bindParam(':available', $available, \PDO::PARAM_BOOL);
        $stmt->bindParam(':pageNumber', $pageNumber, \PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        
        // Vérification de mise à jour
        if ($stmt->rowCount() === 0) {
            echo "Aucune ligne n'a été mise à jour. Vérifiez l'ID.";
        }
    }

    /**
     * Delete a book
     */
    public function delete(): void {
        $id = $this->getId();
        $db = new Database();
        $connection = $db->connect();
        $stmt = $connection->prepare("DELETE FROM book WHERE id = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();

        // Vérification de suppression
        if ($stmt->rowCount() === 0) {
            echo "Aucune ligne n'a été supprimée. Vérifiez l'ID.";
        }
    }

    /**
     * add a book
     */
    public function persist(): void {
        $title = $this->getTitle();
        $author = $this->getAuthor();
        $pageNumber = $this->getPageNumber();
        $available = $this->getAvailable();

        $db = new Database();
        $connection = $db->connect();
        $stmt = $connection->prepare("INSERT INTO book (title, author, pageNumber, available) VALUES (:title, :author, :pageNumber, :available)");
        $stmt->bindParam(':title', $title, \PDO::PARAM_STR);
        $stmt->bindParam(':author', $author, \PDO::PARAM_STR);
        $stmt->bindParam(':pageNumber', $pageNumber, \PDO::PARAM_INT);
        $stmt->bindParam(':available', $available, \PDO::PARAM_BOOL);
        $stmt->execute();
    }
}
