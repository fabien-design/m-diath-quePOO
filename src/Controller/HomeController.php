<?php

namespace App\Controller;

use App\Enum\MediaTypeEnum;
use App\Model\Book;
use App\Model\Album;
use App\Model\Movie;
use App\Utils\SmithWatermanMatchMismatch;
use Exception;

final readonly class HomeController
{

    public function index() : void
    {
        $allMedias = [
            "books" => Book::getBooks(),
            "movies" => Movie::getMovies(),
            "albums" => Album::getAlbums()
        ];

        if(!empty($_POST) && $_POST['search'] !== null && $_POST['search'] !== '') {
            $search = $_POST['search'];
            $categories = 'all';
            if(isset($_POST['categories']) && !empty($_POST['categories'])) {
                $categ = $_POST['categories'];
                $categories = MediaTypeEnum::from($categ) ? MediaTypeEnum::from($categ) : 'all';
            }
            $allMedias = $this->search($search, $allMedias, $categories);

        }
        include "../src/view/welcome.php";
    }

    public function loadFixtures() : void 
    {
        include "../src/view/loadFixtures.php";
    }

    public function add() : void
    {
        if (isset($_SESSION['user']) && !in_array('user', $_SESSION['user']['roles'])) {
            return;
        }

        include "../src/view/add.php";
    }

    private static function search($search, $medias, $categories) : ?array
    {
        function removeAccents($str) {
            return str_replace('ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy', $str);
        }
        
        function filtrerMotsCourts($str) {
            $mots = explode(' ', $str);
            $motsFiltres = array_filter($mots, function($mot) {
                return strlen($mot) > 2; // Garde uniquement les mots de plus de 2 caractères
            });
            return implode(' ', $motsFiltres);
        }
        
        // Fonction qui implémente un algorithme similaire à Levenshtein en utilisant Smith-Waterman
        function smithWatermanLevenshtein($str1, $str2, $matchValue = 2, $mismatchValue = -1) {
            // Filtrer les mots de moins de 3 caractères
            $str1 = removeAccents($str1);
            $str2 = removeAccents($str2);
            $str1 = filtrerMotsCourts($str1);
            $str2 = filtrerMotsCourts($str2);
        
            $length1 = strlen($str1);
            $length2 = strlen($str2);
        
            // Initialiser la classe de correspondance/mismatch
            $scoring = new SmithWatermanMatchMismatch($matchValue, $mismatchValue);
        
            // Matrice de scores
            $matrix = array_fill(0, $length1 + 1, array_fill(0, $length2 + 1, 0));
        
            // Variables pour suivre le meilleur score
            $maxScore = 0;
        
            // Remplissage de la matrice de comparaison
            for ($i = 1; $i <= $length1; $i++) {
                for ($j = 1; $j <= $length2; $j++) {
                    // Comparaison des caractères actuels des deux chaînes
                    $match = $scoring->compare($str1, $i - 1, $str2, $j - 1);
        
                    // Calculer le score maximum local (Smith-Waterman)
                    $scoreDiag = $matrix[$i - 1][$j - 1] + $match;
                    $scoreLeft = $matrix[$i][$j - 1] + $scoring->min();
                    $scoreUp = $matrix[$i - 1][$j] + $scoring->min();
        
                    // Prendre le max entre le match, insertion, suppression, ou 0
                    $matrix[$i][$j] = max(0, $scoreDiag, $scoreLeft, $scoreUp);
        
                    // Garder trace du meilleur score trouvé
                    $maxScore = max($maxScore, $matrix[$i][$j]);
                }
            }
        
            return $maxScore;
        }
        
        // Fonction de recherche améliorée avec Smith-Waterman
        function rechercheSmithWaterman($recherche, $medias) {
            $resultats = [];
            foreach ($medias as $media) {
                $titreMedia = strtolower($media->getTitle());
                $recherche = strtolower($recherche);

                if (strpos($titreMedia, $recherche) !== false) {
                    $resultats[] = [
                        'media' => $media,
                        'score' => 100
                    ];
                    continue;
                }
        
                // Calculer le score Smith-Waterman pour chaque media
                $score = smithWatermanLevenshtein($recherche, $titreMedia);
        
                // Ajouter au résultat si le score dépasse un certain seuil
                if ($score > 10) { // Ajuster ce seuil selon ton besoin
                    $resultats[] = [
                        'media' => $media,
                        'score' => $score,
                    ];
                }
            }
        
            // Trier les résultats par score décroissant
            if (!empty($resultats) && count($resultats) > 1) {
                usort($resultats, function($a, $b) {
                    return $b['score'] - $a['score'];
                });
            }
        
            // Retourner uniquement les albums
            return array_map(function($resultat) {
                return $resultat['media'];
            }, $resultats);
        }
        
        
        // Appel de la fonction de recherche
        $mediasFiltered = [];
        if($categories !== 'all') {
            $mediasFiltered[$categories] = rechercheSmithWaterman($search, $medias[$categories]);
            return $mediasFiltered;
        }
        $mediasFiltered['albums'] = rechercheSmithWaterman($search, $medias['albums']);
        $mediasFiltered['books'] = rechercheSmithWaterman($search, $medias['books']);
        $mediasFiltered['movies'] = rechercheSmithWaterman($search, $medias['movies']);
        if (empty($mediasFiltered)) {
            echo "<h1 class='text-3xl text-center text-red-500 font-bold'>Aucun résultat pour : $search</h1>"; 
        }

        return $mediasFiltered;
    }

}
