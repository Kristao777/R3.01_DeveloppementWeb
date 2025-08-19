<?php

namespace App\R301\Controller;

use App\R301\Model\Commentaire;
use PDO;

class CommentaireController {

    private $commentaireModel;

    public function __construct() {
        $this->commentaireModel = new Commentaire();
    }

    function ajouter($id_recette) {

        // utilisateur anonyme si aucun utilisateur connecté
        if(isset($_SESSION['id'])) {
            $pseudo = $_SESSION['identifiant'];
        } else {
            $pseudo = 'Anonyme';
        }

        // récupération du commentaire du formulaire et insertion dans la base de données
        $commentaire = $_POST['commentaire'];
        // préparation de la requête d'insertion dans la base de données
        $this->commentaireModel->add($pseudo, $id_recette, $commentaire);
        // redirection vers la page de détail de la recette après l'ajout du commentaire
        header('Location: ?c=Recette&a=detail&id='.$id_recette);
    }

    // fonction permettant de lister les commmentaires d'une recette
    function lister($id) {
        $params = ['recette_id' => $id];

        // si l'utilisateur est admin, on récupère tous les commentaires de la recette
        // sinon, on récupère uniquement les commentaires validés
        if(isset($_SESSION['isAdmin']) && !$_SESSION['isAdmin'] || !isset($_SESSION['isAdmin'])) {
            $params['isApproved'] = 1;
        }

        $commentaires = $this->commentaireModel->findBy($params);
        return $commentaires;
    }

    // fonction permettant de lister tous les commentaires
    function getAll() {
        $commentaires = $this->commentaireModel->findAll();

        // affichage des commentaires
        require_once __DIR__. DIRECTORY_SEPARATOR. '..'. DIRECTORY_SEPARATOR. 'Views'. DIRECTORY_SEPARATOR. 'Commentaire' . DIRECTORY_SEPARATOR. 'liste.php';
    }

    // fonction permettant de supprimer un commentaire
    function supprimer($id) {

        $suppressionOk = $this->commentaireModel->delete($id);

        // Si la suppression est réussie, on redirige vers la liste des commentaires
        if(!$suppressionOk) {
            $_SESSION['message'] = ['danger' => 'Erreur dans la suppression du commentaire'];
        } else {
            $_SESSION['message'] = ['success' => 'Commentaire supprimé avec succès'];
        }
        // redirection vers la liste des commentaires
        header('Location: ?c=Commentaire&a=lister');
    }

    // Fonction permettant de lister les commentaires a approuver
    function aApprouver() {

        $comments = $this->commentaireModel->findBy(['isApproved' => 0]);
        
        require_once(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Views'.DIRECTORY_SEPARATOR. 'Commentaire' . DIRECTORY_SEPARATOR.'aApprouver.php');
    }

    // Fonction permettant de valider un commentaire
    function valider($id) {
        // préparation de la requête de mise à jour dans la base de données
        $requete = $this->commentaireModel->getConnection()->prepare("UPDATE comments SET isApproved = 1 WHERE id = :id");
        $requete->bindParam(':id', $id);
        
        // exécution de la requête
        $validationOk = $requete->execute();
        
        if($validationOk) {
            $_SESSION['message'] = ['success' => 'Commentaire validé avec succès'];
            
            // redirection vers la vue de validation effectuée
            header('Location:?c=Commentaire&a=aApprouver');
        } else {
            $_SESSION['message'] = ['danger' => 'Erreur dans la validation du commentaire'];
        }
    }

    // Fonction permettant de compter le nombre de commentaires non validés
    function nbAValider() {
        if(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']) {

            // préparation de la requête de sélection dans la base de données
            $requete = $this->commentaireModel->getConnection()->prepare("SELECT COUNT(*) as nbCommentairesNonValides FROM comments WHERE isApproved = 0 OR isApproved IS NULL");
                    
            // exécution de la requête et récupération des données
            $requete->execute();
            $resultat = $requete->fetch(PDO::FETCH_ASSOC);

            echo $resultat['nbCommentairesNonValides'];

        } else {
            echo 0;
        }
    }

}