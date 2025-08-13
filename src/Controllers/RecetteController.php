<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Models' . DIRECTORY_SEPARATOR . 'Recette.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Models' . DIRECTORY_SEPARATOR . 'Favori.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Models' . DIRECTORY_SEPARATOR . 'Commentaire.php';


class RecetteController {

    private $recetteModel;
    private $favoriModel;
    private $commentaireModel;

    public function __construct() {
        $this->recetteModel = new Recette();
        $this->favoriModel = new Favori();
        $this->commentaireModel = new Commentaire();
    }

    // Fonction permettant d'ajouter une nouvelle recette
    function ajouter() {
        require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . 'Recette' . DIRECTORY_SEPARATOR . 'ajout.php';
    }

    function modifier() {

        $recipe = $this->recetteModel->find($_GET['id']);

        require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . 'Recette' . DIRECTORY_SEPARATOR . 'modif.php';
    }

    // Fonction permettant d'enregistrer une nouvelle recette
    function enregistrer() {

        // récupération des données de formulaire
        $titre = $_POST['titre'];
        $description = $_POST['description'];
        $auteur = $_POST['auteur'];

        // l'ancienne image est conservée si aucune n'a été choisie
        // sinon, une nouvelle image est créée (erreur 4 = image non choisie)
        if($_FILES['image']['error'] == 4) {
            $recipe = $this->recetteModel->find($_GET['id']);
            $image = $recipe['image'];
        } else {
            $image = $_FILES['image']['name'];
            $target_dir = "upload/";
            $target_file = $target_dir. basename($_FILES["image"]["name"]);
            move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        }
        
        // préparation de la requête d'insertion dans la base de données

        // création ou modification d'une recette
        if (isset($_GET['id'])) {
            // modification d'une recette
            $ajoutOk = $this->recetteModel->update($_GET['id'], $titre, $description, $auteur, $image);
        } else {
            // création d'une nouvelle recette
            $ajoutOk = $this->recetteModel->add($titre, $description, $auteur, $image);
        }
        
        if($ajoutOk) {
            // redirection vers la vue d'enregistrement effectué
            require_once(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Views'.DIRECTORY_SEPARATOR. 'Recette' .DIRECTORY_SEPARATOR.'enregistrement.php');
        } else {
            echo 'Erreur lors de l\'enregistrement de la recette.';
        }
    }

    // Fonction permettant de lister les recettes
    function index() {
        // préparation de la requête d'insertion dans la base de données
        $recipes = $this->recetteModel->findAll();

        require_once(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Views'.DIRECTORY_SEPARATOR. 'Recette' . DIRECTORY_SEPARATOR .'liste.php');
    }

    function detail($id) {

        // Ajout du contrôleur des favoris
        $favoriController = new FavoriController();
        $existe = $favoriController->existe($pdo, $id, isset($_SESSION['id']) ? $_SESSION['id']:null);
        
        // préparation de la requête de sélection dans la base de données
        $recipe = $this->recetteModel->find($id);

        // Ajout des commentaires
        $commentaireController = new CommentaireController();
        $commentaires = $commentaireController->lister($pdo, $id);

        require_once(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Views'.DIRECTORY_SEPARATOR. 'Recette' . DIRECTORY_SEPARATOR .'detail.php');
    }

    // Fonction permettant de supprimer une recette
    function supprimer($id) {

        // Suppression des favoris liés à la recette
        $requete = $pdo->prepare("DELETE FROM favoris WHERE recette_id = :id");
        $requete->bindParam(':id', $id);
        
        // exécution de la requête
        $suppressionOk = $requete->execute();

        // Suppression des commentaires liés à la recette
        $requete = $pdo->prepare("DELETE FROM comments WHERE recette_id = :id");
        $requete->bindParam(':id', $id);
        
        // exécution de la requête
        $suppressionOk = $requete->execute();

        // préparation de la requête de suppression dans la base de données
        $requete = $pdo->prepare("DELETE FROM recettes WHERE id = :id");
        $requete->bindParam(':id', $id);
        
        // exécution de la requête
        $suppressionOk = $requete->execute();
        
        if($suppressionOk) {
            $_SESSION['message'] = ['success' => 'Recette supprimée avec succès'];

            // redirection vers la vue de suppression effectuée
            require_once(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Views'.DIRECTORY_SEPARATOR. 'Recette' . DIRECTORY_SEPARATOR.'liste.php');
        } else {
            $_SESSION['message'] = ['danger' => 'Erreur dans la suppression de la recette'];;
        }
    }

}