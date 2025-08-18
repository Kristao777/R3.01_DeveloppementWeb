<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Models' . DIRECTORY_SEPARATOR . 'Commentaire.php';

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
        $commentaires = $this->commentaireModel->findBy(['recette_id' => $id]);
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

}