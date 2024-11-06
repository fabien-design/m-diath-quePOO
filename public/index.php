<?php
session_start();
require "../vendor/autoload.php";

use App\Router\Router;

// Instantiate the Router
$router = new Router(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '');

$router->get("/", "App\Controller\HomeController@index", "welcome");
$router->post('/', 'App\Controller\HomeController@index', 'searchMedias');
$router->get('/loadFixtures', 'App\Controller\HomeController@loadFixtures', 'loadFixtures');

// main routes
$router->get('/add', 'App\Controller\HomeController@add', 'add');
$router->get("/movies", "App\Controller\MovieController@index", "movies");
$router->get("/books", "App\Controller\BookController@index", "books");
$router->get("/albums", "App\Controller\AlbumController@index", "albums");

// show routes
$router->get("/movies/{id}", "App\Controller\MovieController@show", "showMovies");
$router->get("/books/{id}", "App\Controller\BookController@show", "showBooks");
$router->get("/albums/{id}", "App\Controller\AlbumController@show", "showAlbums");

$router->post("/movies", "App\Controller\MovieController@create", "createMovies");
$router->post("/books", "App\Controller\BookController@create", "createBooks");
$router->post("/albums", "App\Controller\AlbumController@create", "createAlbums");
$router->get("/movies/{id}/edit", "App\Controller\MovieController@edit", "editMovies");
$router->post("/movies/{id}/edit", "App\Controller\MovieController@update", "updateMovies");
$router->get("/books/{id}/edit", "App\Controller\BookController@edit", "editBooks");
$router->post("/books/{id}/edit", "App\Controller\BookController@update", "updateBooks");
$router->get("/albums/{id}/edit", "App\Controller\AlbumController@edit", "editAlbums");
$router->post("/albums/{id}/edit", "App\Controller\AlbumController@update", "updateAlbums");

$router->get("/movies/{id}/delete", "App\Controller\MovieController@delete", "deleteMovies");
$router->get("/books/{id}/delete", "App\Controller\BookController@delete", "deleteBooks");
$router->get("/albums/{id}/delete", "App\Controller\AlbumController@delete", "deleteAlbums");

// borrow and return routes
$router->post("/movies/{id}/borrow", "App\Controller\MovieController@borrow", "borrowMovies");
$router->post("/movies/{id}/return", "App\Controller\MovieController@return", "returnMovies");
$router->post("/books/{id}/borrow", "App\Controller\BookController@borrow", "borrowBooks");
$router->post("/books/{id}/return", "App\Controller\BookController@return", "returnBooks");
$router->post("/albums/{id}/borrow", "App\Controller\AlbumController@borrow", "borrowAlbums");
$router->post("/albums/{id}/return", "App\Controller\AlbumController@return", "returnAlbums");

// authantication routes
$router->get("/login", "App\Controller\AuthController@login", "login");
$router->post("/login", "App\Controller\AuthController@checkLogin", "checkLogin");
$router->get("/register", "App\Controller\AuthController@register", "register");
$router->post("/register", "App\Controller\AuthController@create", "createAccount");
$router->get("/logout", "App\Controller\AuthController@logout", "logout");

$router->run();
