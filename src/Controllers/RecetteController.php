<?php

class RecetteController {

    // Fonction permettant d'ajouter une nouvelle recette
    function ajouter() {
        require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . 'Recette' . DIRECTORY_SEPARATOR . 'ajout.php';
    }

    // Fonction permettant d'enregistrer une nouvelle recette
    function enregistrer($pdo) {
        // récupération des données de formulaire
        $titre = $_POST['titre'];
        $description = $_POST['description'];
        $auteur = $_POST['auteur'];

        // préparation de la requête d'insertion dans la base de données

        /** @var PDO $pdo **/
        $requete = $pdo->prepare('INSERT INTO recettes (titre, description, auteur, date_creation) VALUES (:titre, :description, :auteur, NOW())');
        $requete->bindParam(':titre', $titre);
        $requete->bindParam(':description', $description);
        $requete->bindParam(':auteur', $auteur);

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

}