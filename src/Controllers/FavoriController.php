<?php

namespace App\R301\Controller;

use App\R301\Model\Favori;
use App\R301\Model\Recette;

class FavoriController {
    
    private $favoriModel;
    private $recetteModel;

    public function __construct() {
        $this->favoriModel = new Favori();
        $this->recetteModel = new Recette();
    }

    function ajouter($id_recette) {
        // récupération de l'id de l'utilisateur connecté
        $id_utilisateur = $_SESSION['id'];
        
        // vérification si l'utilisateur a déjà ajouté cette recette à ses favoris
        $ajout = $this->favoriModel->findBy(['user_id' => $id_utilisateur, 'recette_id' => $id_recette]);
        
        if (count($ajout) == 0) {
            // l'utilisateur n'a pas déjà ajouté cette recette à ses favoris, on l'ajoute
            $ajoutOk = $this->favoriModel->add($id_utilisateur, $id_recette);

            if (!$ajoutOk) {
                $_SESSION['message'] = ['error' => 'Erreur lors de l\'ajout aux favoris'];
            } else {
            $_SESSION['message'] = ['success' => 'Recette ajoutée aux favoris'];
            }    

        } else {
            // l'utilisateur a déjà ajouté cette recette à ses favoris, on le supprime des favoris
            $supprimerOk = $this->favoriModel->delete($ajout[0]['id']);
            if (!$supprimerOk) {
                $_SESSION['message'] = ['error' => 'Erreur lors de la suppression des favoris'];
            } else {
            $_SESSION['message'] = ['success' => 'Recette supprimée des favoris'];
        }
        }

        // redirection vers la page de la recette pour afficher un message de confirmation
        header('Location: ?c=Recette&a=detail&id='. $id_recette);

    }

    // Fonction permettant de vérifier si une recette est déjà dans les favoris d'un utilisateur
    function existe($id_recette, $id_utilisateur)
    { 
        // récupération de l'id de l'utilisateur connecté
        $favori = $this->favoriModel->findBy(['user_id' => $id_utilisateur, 'recette_id' => $id_recette]);

        return count($favori) > 0;
    }

    function mesRecettesFavoris() {
        require_once __DIR__. DIRECTORY_SEPARATOR. '..'. DIRECTORY_SEPARATOR. 'Views'. DIRECTORY_SEPARATOR. 'User'. DIRECTORY_SEPARATOR.'favoris.php';
    }

    // Fonction permettant de récupérer les recettes favorites d'un utilisateur
    function getFavoris($id_utilisateur) {
        $favoris = $this->favoriModel->findBy(['user_id' => $id_utilisateur]);
        $recettesFavori = [];
        foreach ($favoris as $favori) {
            $recette = $this->recetteModel->find($favori['recette_id']);
            $recettesFavori[] = $recette;
        }
        echo json_encode($recettesFavori);
    }

}