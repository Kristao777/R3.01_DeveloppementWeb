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
        header('Location: ?c=detail&id='.$id_recette);
    }

    // fonction permettant de lister les commmentaires d'une recette
    function lister($id) {
        $requete = $pdo->prepare("SELECT * FROM comments WHERE recette_id = :recette_id");
        $requete->bindParam(':recette_id',$id);
        $requete->execute();
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }

    // fonction permettant de lister tous les commentaires
    function getAll() {
        $requete = $pdo->query("SELECT * FROM comments ORDER BY create_time DESC");
        $commentaires = $requete->fetchAll(PDO::FETCH_ASSOC);

        // affichage des commentaires
        require_once __DIR__. DIRECTORY_SEPARATOR. '..'. DIRECTORY_SEPARATOR. 'Views'. DIRECTORY_SEPARATOR. 'Comment' . DIRECTORY_SEPARATOR. 'liste.php';
    }

    // fonction permettant de supprimer un commentaire
    function supprimer($id) {
        $requete = $pdo->prepare("DELETE FROM comments WHERE id = :id");
        $requete->bindParam(':id', $id);
        $requete->execute();
        $_SESSION['message'] = ['success' => 'Commentaire supprimé avec succès'];
        // redirection vers la page de détail de la recette après la suppression du commentaire
        header('Location: ?c=listeComments');
    }

}