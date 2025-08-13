<?php

    session_start();

    // import de la classe RecetteController
    require_once(__DIR__.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR.'RecetteController.php');
    
    // import de la classe ContactController
    require_once(__DIR__.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR.'ContactController.php');
    
    // import de la classe UserController
    require_once(__DIR__.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR.'UserController.php');
    
    // import de la classe FavoriController
    require_once(__DIR__.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR.'FavoriController.php');
    
    // import de la classe CommentController
    require_once(__DIR__.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR.'CommentController.php');
    
    // connexion à la base de données
    require_once(__DIR__.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'Models'.DIRECTORY_SEPARATOR.'connectDb.php');
    
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
                    $recetteController->index($pdo);
                    break;
                case 'ajouter':
                    $recetteController->ajouter();
                    break;
                case 'enregistrer':
                    $recetteController->enregistrer($pdo);
                    break;
                case 'detail':
                    $recetteController->detail($pdo, isset($_GET['id']) ? $_GET['id'] : null);
                    break;
                case 'modifier':
                    $recetteController->modifier($pdo, isset($_GET['id']) ? $_GET['id'] : null);
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
            $contactController->enregister($pdo);
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
                    $userController->enregistrer($pdo);
                    break;
                case 'connexion':
                    $userController->connexion();
                    break;
                case 'connecter':
                    $userController->verifieConnexion($pdo);
                    break;
                case 'profil':
                    $userController->profil($pdo);
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
                    $favoriController->ajouter($pdo, $_GET['id']);
                    break;
                case 'mesFavoris':
                    $favoriController = new FavoriController();
                    $favoriController->mesRecettesFavoris();
                    break;
                case 'getFavoris':
                    $favoriController = new FavoriController();
                    $favoriController->getFavoris($pdo, $_GET['id']);
                    break;
                default:
                    echo "Page non trouvée";
            }
            break;
        // route pour la gestion des commentaires
        case 'Commentaire':
            $commentaireController = new CommentController();
            switch ($action) {
                case 'ajouter':
                    $commentaireController->ajouter($pdo, $_GET['id']);
                    break;
                case 'lister':
                    $commentaireController->getAll($pdo);
                    break;
                case 'supprimer':
                    $commentaireController->supprimer($pdo, $_GET['id']);
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
