<?php

class FavoriController {

    function ajouter($pdo, $id_recette) {
        // récupération de l'id de l'utilisateur connecté
        $id_utilisateur = $_SESSION['id'];
        
        // vérification si l'utilisateur a déjà ajouté cette recette à ses favoris
        $requete = $pdo->prepare("SELECT * FROM favoris WHERE user_id = :user_id AND recette_id = :recette_id");
        $requete->bindParam(':user_id', $id_utilisateur);
        $requete->bindParam(':recette_id', $id_recette);
        $requete->execute();
        
        if (!$requete->fetch()) {
            // l'utilisateur n'a pas déjà ajouté cette recette à ses favoris, on l'ajoute
            $requete = $pdo->prepare("INSERT INTO favoris (user_id, recette_id, create_time) VALUES (:user_id, :recette_id, NOW())");
            $requete->bindParam(':user_id', $id_utilisateur);
            $requete->bindParam(':recette_id', $id_recette);
            $requete->execute();
            $_SESSION['message'] = ['success' => 'Recette ajoutée aux favoris'];

        } else {
            // l'utilisateur a déjà ajouté cette recette à ses favoris, on le supprime des favoris
            $requete = $pdo->prepare("DELETE FROM favoris WHERE user_id = :user_id AND recette_id = :recette_id");
            $requete->bindParam(':user_id', $id_utilisateur);
            $requete->bindParam(':recette_id', $id_recette);
            $requete->execute();
            $_SESSION['message'] = ['success' => 'Recette supprimée des favoris'];
        }

        // redirection vers la page de la recette pour afficher un message de confirmation
        header('Location: ?c=Recette&a=detail&id='. $id_recette);

    }

    // Fonction permettant de vérifier si une recette est déjà dans les favoris d'un utilisateur
    function existe($pdo,$id_recette, $id_utilisateur)
    { 
        // récupération de l'id de l'utilisateur connecté
        $requete = $pdo->prepare("SELECT * FROM favoris WHERE user_id = :user_id AND recette_id = :recette_id");
        $requete->bindParam(':user_id', $id_utilisateur);
        $requete->bindParam(':recette_id', $id_recette);
        $requete->execute();

        return $requete->fetch();
    }

    function mesRecettesFavoris() {
        require_once __DIR__. DIRECTORY_SEPARATOR. '..'. DIRECTORY_SEPARATOR. 'Views'. DIRECTORY_SEPARATOR. 'User'. DIRECTORY_SEPARATOR.'favoris.php';
    }

    // Fonction permettant de récupérer les recettes favorites d'un utilisateur
    function getFavoris($pdo, $id_utilisateur) {
        $requete = $pdo->prepare("SELECT r.* FROM favoris f JOIN recettes r ON f.recette_id = r.id WHERE f.user_id = :user_id");
        $requete->bindParam(':user_id', $id_utilisateur);
        $requete->execute();
        echo json_encode($requete->fetchAll(PDO::FETCH_ASSOC));
    }

}