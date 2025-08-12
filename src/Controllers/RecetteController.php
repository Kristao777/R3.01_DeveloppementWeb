<?php

class RecetteController {

    // Fonction permettant d'ajouter une nouvelle recette
    function ajouter() {
        require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . 'Recette' . DIRECTORY_SEPARATOR . 'ajout.php';
    }

    function modifier($pdo) {

        /** @var PDO $pdo **/
        $requete = $pdo->prepare("SELECT * FROM recettes WHERE id = :id");
        $requete->bindParam(':id', $_GET['id']);
        
        // exécution de la requête et récupération des données
        $requete->execute();
        $recipe = $requete->fetch(PDO::FETCH_ASSOC);

        require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . 'Recette' . DIRECTORY_SEPARATOR . 'modif.php';
    }

    // Fonction permettant d'enregistrer une nouvelle recette
    function enregistrer($pdo) {

        // récupération des données de formulaire
        $titre = $_POST['titre'];
        $description = $_POST['description'];
        $auteur = $_POST['auteur'];

        // l'ancienne image est conservée si aucune n'a été choisie
        // sinon, une nouvelle image est créée (erreur 4 = image non choisie)
        if($_FILES['image']['error'] == 4) {
            $requete = $pdo->prepare("SELECT * FROM recettes WHERE id = :id");
            $requete->bindParam(':id', $_GET['id']);
            $requete->execute();
            $recipe = $requete->fetch(PDO::FETCH_ASSOC);
            $image = $recipe['image'];
        } else {
            $image = $_FILES['image']['name'];
            $target_dir = "upload/";
            $target_file = $target_dir. basename($_FILES["image"]["name"]);
            move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        }
        
        // préparation de la requête d'insertion dans la base de données

        // création ou modification d'une recette
        /** @var PDO $pdo **/
        if (isset($_GET['id'])) {
            // modification d'une recette
            $requete = $pdo->prepare("UPDATE recettes SET titre = :titre, description = :description, auteur = :auteur, image = :image WHERE id = :id");
            $requete->bindParam(':id', $_GET['id']);
        } else {
            // création d'une nouvelle recette
            $requete = $pdo->prepare("INSERT INTO recettes (titre, description, auteur, image, date_creation) VALUES (:titre, :description, :auteur, :image, NOW())");
        }
        $requete->bindParam(':titre', $titre);
        $requete->bindParam(':description', $description);
        $requete->bindParam(':auteur', $auteur);
        $requete->bindParam(':image', $image);

        // exécution de la requête
        $ajoutOk = $requete->execute();
        
        if($ajoutOk) {
            // redirection vers la vue d'enregistrement effectué
            require_once(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Views'.DIRECTORY_SEPARATOR. 'Recette' .DIRECTORY_SEPARATOR.'enregistrement.php');
        } else {
            echo 'Erreur lors de l\'enregistrement de la recette.';
        }
    }

    // Fonction permettant de lister les recettes
    function index($pdo) {
        // préparation de la requête d'insertion dans la base de données

        /** @var PDO $pdo **/
        $requete = $pdo->prepare("SELECT * FROM recettes");
        
        // exécution de la requête et récupération des données
        $requete->execute();
        $recipes = $requete->fetchAll(PDO::FETCH_ASSOC);

        require_once(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Views'.DIRECTORY_SEPARATOR. 'Recette' . DIRECTORY_SEPARATOR .'liste.php');
    }

    function detail($pdo, $id) {
        // préparation de la requête d'insertion dans la base de données

        /** @var PDO $pdo **/
        $requete = $pdo->prepare("SELECT * FROM recettes WHERE id = :id");
        $requete->bindParam(':id', $id);
        
        // exécution de la requête et récupération des données
        $requete->execute();
        $recipe = $requete->fetch(PDO::FETCH_ASSOC);

        require_once(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Views'.DIRECTORY_SEPARATOR. 'Recette' . DIRECTORY_SEPARATOR .'detail.php');
    }

}