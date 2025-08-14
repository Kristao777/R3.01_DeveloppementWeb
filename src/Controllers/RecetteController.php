<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Models' . DIRECTORY_SEPARATOR . 'Recette.php';

class RecetteController {

    private $recetteModel;

    public function __construct() {
        $this->recetteModel = new Recette();
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

        // création ou modification d'une recette
        if (isset($_GET['id'])) {
            // modification d'une recette
            $ajoutOk = $this->recetteModel->update($_GET['id'], $titre, $description, $auteur, $image);
        } else {
            // création d'une nouvelle recette
            $ajoutOk = $this->recetteModel->add($titre, $description, $auteur, $image);
        }

        // si l'ajout a réussi, on redirige vers la page d'enregistrement
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

        $recipe = $this->recetteModel->find($id);

        require_once(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Views'.DIRECTORY_SEPARATOR. 'Recette' . DIRECTORY_SEPARATOR .'detail.php');
    }

}