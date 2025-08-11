<?php

    // import de la classe RecetteController
    require_once(__DIR__.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR.'RecetteController.php');
    
    // import de la classe RecetteController
    require_once(__DIR__.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR.'ContactController.php');
    
    // connexion à la base de données
    require_once(__DIR__.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'Models'.DIRECTORY_SEPARATOR.'connectDb.php');
    
    // ajout de l'en tête
    require_once(__DIR__.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'Views'.DIRECTORY_SEPARATOR.'header.php');

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
        // route pour la page d'accueil
        case 'home':
            require_once(__DIR__.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR.'homeController.php');
            break;
        // route par défaut
        default:
            echo "Page non trouvée";
    }
    
    // ajout du pied de page
    require_once(__DIR__.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'Views'.DIRECTORY_SEPARATOR.'footer.php');
