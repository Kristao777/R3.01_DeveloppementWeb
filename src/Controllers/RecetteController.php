<?php

namespace App\R301\Controller;

use App\R301\Model\Recette;
use App\R301\Model\Favori;
use App\R301\Model\Commentaire;
use App\R301\Controller\FavoriController;
use App\R301\Controller\CommentaireController;

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
        $typePlat = $_POST['type'];

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
            $ajoutOk = $this->recetteModel->update($_GET['id'], $titre, $description, $auteur, $typePlat, $image);
        } else {
            // création d'une nouvelle recette
            $ajoutOk = $this->recetteModel->add($titre, $description, $auteur, $typePlat, $image);
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

         // verifier l'existence d'un filtre des recettes par type de plat
        if (isset($_GET['filtre']) && $_GET['filtre']!= 'all') {
            $params = ['type_plat' => $_GET['filtre']];
            $recipes = $this->recetteModel->findBy($params);
        } else {
            // si aucun filtre n'est appliqué, on récupère toutes les recettes
            $recipes = $this->recetteModel->findAll();
        }

        require_once(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Views'.DIRECTORY_SEPARATOR. 'Recette' . DIRECTORY_SEPARATOR .'liste.php');
    }

        // Fonction permettant de lister les recettes
    function indexJson() {

        // Lister toutes les recettes
        $recipes = $this->recetteModel->findAll();

        // Renvoyer les données au format JSON
        header('Content-Type: application/json');
        echo json_encode($recipes);
    }

    function detail($id) {

        // Ajout du contrôleur des favoris
        $favoriController = new FavoriController();
        $existe = $favoriController->existe($id, isset($_SESSION['id']) ? $_SESSION['id']:null);
        
        // préparation de la requête de sélection dans la base de données
        $recipe = $this->recetteModel->find($id);

        // Ajout des commentaires
        $commentaireController = new CommentaireController();
        $commentaires = $commentaireController->lister($id);

        require_once(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Views'.DIRECTORY_SEPARATOR. 'Recette' . DIRECTORY_SEPARATOR .'detail.php');
    }

    // Fonction permettant de supprimer une recette
    function supprimer($id) {

        // Suppression des favoris liés à la recette
        $favoris = $this->favoriModel->findBy(['recette_id' => $id]);
        foreach ($favoris as $favori) {
            $this->favoriModel->delete($favori['id']);
        }
        // Suppression des commentaires liés à la recette
        $comments = $this->commentaireModel->findBy(['recette_id' => $id]);
        foreach ($comments as $comment) {
            $this->commentaireModel->delete($comment['id']);
        }
        // préparation de la requête de suppression dans la base de données
        $suppressionOk = $this->recetteModel->delete($id);
        
        // Si la suppression est réussie, on redirige vers la liste des recettes
        // Sinon, on affiche un message d'erreur
        if($suppressionOk) {
            $_SESSION['message'] = ['success' => 'Recette supprimée avec succès'];

            // redirection vers la vue de suppression effectuée
            header('Location: ?c=Recette&a=index');
        } else {
            $_SESSION['message'] = ['danger' => 'Erreur dans la suppression de la recette'];;
        }
    }

}