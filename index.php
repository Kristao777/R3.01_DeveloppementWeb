<?php

    session_start();

    require 'vendor/autoload.php';

    // inclusion des contrôleurs
    
    Use App\R301\Controller\RecetteController;
    Use App\R301\Controller\ContactController;
    Use App\R301\Controller\UserController;
    Use App\R301\Controller\FavoriController;
    Use App\R301\Controller\CommentaireController;

    // ajout de l'en tête
    if(!isset($_GET["x"])) require_once(__DIR__.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'Views'.DIRECTORY_SEPARATOR.'header.php');

    // mise en place de la route actuelle
    $controller = isset($_GET['c'])? $_GET['c'] : 'home';
    $action = isset($_GET['a'])? $_GET['a'] : 'index';

    // définition des routes disponibles
    switch ($controller) {

        // route pour la gestion des recettes
        case 'Recette':
            $recetteController = new RecetteController();
            switch ($action) {
                case 'index':
                    $recetteController->index();
                    break;
                case 'ajouter':
                    $recetteController->ajouter();
                    break;
                case 'enregistrer':
                    $recetteController->enregistrer();
                    break;
                case 'detail':
                    $recetteController->detail(isset($_GET['id']) ? $_GET['id'] : null);
                    break;
                case 'modifier':
                    $recetteController->modifier(isset($_GET['id']) ? $_GET['id'] : null);
                    break;
                case 'supprimer':
                    $recetteController->supprimer(isset($_GET['id']) ? $_GET['id'] : null);
                    break;
                default:
                    echo "Action non trouvée";
            }
            break;
        // route pour la gestion des contacts
        case 'Contact':
            $contactController = new ContactController();
            switch ($action) {
                case 'ajouter':
                    $contactController->ajouter();
                    break;
                case 'enregistrer':
                    $contactController->enregister();
                    break;
                default:
                    echo "Action non trouvée";
            }
            break;
        // route pour la gestion des utilisateurs
        case 'User':
            $userController = new UserController();
            switch ($action) {
                case 'inscription':
                    $userController->inscription();
                    break;
                case 'inscrire':
                    $userController->enregistrer();
                    break;
                case 'connexion':
                    $userController->connexion();
                    break;
                case 'connecter':
                    $userController->verifieConnexion();
                    break;
                case 'profil':
                    $userController->profil();
                    break;
                case 'deconnexion':
                    $userController->deconnexion();
                    break;
                default:
                    echo "Page non trouvée";
                    break;
            }
            break;
        // route pour la gestion des utilisateurs
        case 'Favori':
            $favoriController = new FavoriController;
            switch ($action) {
                case 'ajouter':
                    $favoriController = new FavoriController();
                    $favoriController->ajouter($_GET['id']);
                    break;
                case 'mesFavoris':
                    $favoriController = new FavoriController();
                    $favoriController->mesRecettesFavoris();
                    break;
                case 'getFavoris':
                    $favoriController = new FavoriController();
                    $favoriController->getFavoris($_GET['id']);
                    break;
                default:
                    echo "Page non trouvée";
            }
            break;
        // route pour la gestion des commentaires
        case 'Commentaire':
            $commentaireController = new CommentaireController();
            switch ($action) {
                case 'ajouter':
                    $commentaireController->ajouter($_GET['id']);
                    break;
                case 'lister':
                    $commentaireController->getAll();
                    break;
                case 'supprimer':
                    $commentaireController->supprimer($_GET['id']);
                    break;
                default:
                    echo "Page non trouvée";
            }
            break;
        // route pour la page d'accueil
        case 'home':
            require_once(__DIR__.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR.'homeController.php');
            break;
        // route par défaut
        default:
            echo "Page non trouvée";
    }
    
    // ajout du pied de page
    if(!isset($_GET["x"])) require_once(__DIR__.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'Views'.DIRECTORY_SEPARATOR.'footer.php');
