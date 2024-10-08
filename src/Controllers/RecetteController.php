<?php

class RecetteController { 

    // Fonction permettant de lister les recettes
    function index($pdo) {

        // préparation de la requête d'insertion dans la base de données

        /** @var PDO $pdo **/
        // verifier l'existence d'un filtre des recettes par type de plat
        if (isset($_GET['filtre']) && $_GET['filtre']!= 'all') {
            $isAdmin = isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] ? '' : ' AND isApproved = 1';
            $requete = $pdo->prepare("SELECT * FROM recettes WHERE type_plat = :type".$isAdmin);
            $requete->bindParam(':type', $_GET['filtre']);
        } else {
            $isAdmin = isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] ? '' : ' WHERE isApproved = 1';
            $requete = $pdo->prepare("SELECT * FROM recettes".$isAdmin);
        }
        
        // exécution de la requête et récupération des données
        $requete->execute();
        $recipes = $requete->fetchAll(PDO::FETCH_ASSOC);

        require_once(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Views'.DIRECTORY_SEPARATOR. 'Recette' . DIRECTORY_SEPARATOR .'liste.php');
    }

    // Fonction permettant de lister les recettes
    function indexJson($pdo) {

        // préparation de la requête d'insertion dans la base de données

        /** @var PDO $pdo **/
        $isAdmin = isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] ? '' : ' WHERE isApproved = 1';
        $requete = $pdo->prepare("SELECT * FROM recettes".$isAdmin);
        
        // exécution de la requête et récupération des données
        $requete->execute();
        $recipes = $requete->fetchAll(PDO::FETCH_ASSOC);
        // Renvoyer les données au format JSON
        header('Content-Type: application/json');
        echo json_encode($recipes);
    }

    // Fonction permettant d'ajouter une nouvelle recette
    function ajouter() {
        $auteur = isset($_SESSION['mail']) ? $_SESSION['mail'] : '';
        require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . 'Recette' . DIRECTORY_SEPARATOR . 'ajout.php';
    }

    function modifier($pdo) {

        /** @var PDO $pdo **/
        $requete = $pdo->prepare("SELECT * FROM recettes WHERE id = :id");
        $requete->bindParam(':id', $_GET['id']);
        
        // exécution de la requête et récupération des données
        $requete->execute();
        $recipe = $requete->fetch(PDO::FETCH_ASSOC);

        require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . 'Recette' . DIRECTORY_SEPARATOR . 'modif.php';
    }

    // Fonction permettant d'enregistrer une nouvelle recette
    function enregistrer($pdo) {

        // récupération des données de formulaire
        $titre = $_POST['titre'];
        $description = $_POST['description'];
        $auteur = $_POST['auteur'];
        $typePlat = $_POST['type'];
        $isApproved = $_SESSION['isAdmin'] ? true : false;

        // l'ancienne image est conservée si aucune n'a été choisie
        // sinon, une nouvelle image est créée (erreur 4 = image non choisie)
        if($_FILES['image']['error'] == 4) {
            $requete = $pdo->prepare("SELECT * FROM recettes WHERE id = :id");
            $requete->bindParam(':id', $_GET['id']);
            $requete->execute();
            $recipe = $requete->fetch(PDO::FETCH_ASSOC);
            $image = $recipe['image'];
        } else {
            $image = $_FILES['image']['name'];
            $target_dir = "upload/";
            $target_file = $target_dir. basename($_FILES["image"]["name"]);
            move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        }
        
        // préparation de la requête d'insertion dans la base de données

        // création ou modification d'une recette
        /** @var PDO $pdo **/
        if (isset($_GET['id'])) {
            // modification d'une recette
            $requete = $pdo->prepare("UPDATE recettes SET titre = :titre, description = :description, auteur = :auteur, type_plat = :type_plat , image = :image, isApproved = :isApproved WHERE id = :id");
            $requete->bindParam(':id', $_GET['id']);
        } else {
            // création d'une nouvelle recette
            $requete = $pdo->prepare("INSERT INTO recettes (titre, description, auteur, type_plat, image, isApproved, date_creation) VALUES (:titre, :description, :auteur, :type_plat, :image, :isApproved, NOW())");
        }
        $requete->bindParam(':titre', $titre);
        $requete->bindParam(':description', $description);
        $requete->bindParam(':auteur', $auteur);
        $requete->bindParam(':type_plat', $typePlat);
        $requete->bindParam(':image', $image);
        $requete->bindParam(':isApproved', $isApproved);

        // exécution de la requête
        $ajoutOk = $requete->execute();
        
        if($ajoutOk) {
            // redirection vers la vue d'enregistrement effectué
            require_once(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Views'.DIRECTORY_SEPARATOR. 'Recette' .DIRECTORY_SEPARATOR.'enregistrement.php');
        } else {
            $_SESSION['message'] = ['danger' => 'Erreur d\'enregistrement de la recette'];
        }
    }

    function detail($pdo, $id) {

        // Ajout du contrôleur des favoris
        $favoriController = new FavoriController();
        $existe = $favoriController->existe($pdo, $id, isset($_SESSION['id']) ? $_SESSION['id']:null);
        
        // préparation de la requête de sélection dans la base de données

        /** @var PDO $pdo **/
        $requete = $pdo->prepare("SELECT * FROM recettes WHERE id = :id");
        $requete->bindParam(':id', $id);
        
        // exécution de la requête et récupération des données
        $requete->execute();
        $recipe = $requete->fetch(PDO::FETCH_ASSOC);

        // Ajout des commentaires
        $commentaireController = new CommentController();
        $commentaires = $commentaireController->listerParRecette($pdo, $id);

        require_once(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Views'.DIRECTORY_SEPARATOR. 'Recette' . DIRECTORY_SEPARATOR .'detail.php');
    }

    // Fonction permettant de supprimer une recette
    function supprimer($pdo, $id) {

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
            $_SESSION['message'] = ['danger' => 'Erreur dans la suppression de la recette'];
        }
    }

    // Fonction permettant de lister les recettes a approuver
    function aApprouver($pdo) {
        // préparation de la requête de sélection dans la base de données
        $requete = $pdo->prepare("SELECT * FROM recettes WHERE isApproved = 0");
        
        // exécution de la requête et récupération des données
        $requete->execute();
        $recipes = $requete->fetchAll(PDO::FETCH_ASSOC);
        
        require_once(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Views'.DIRECTORY_SEPARATOR. 'Recette' . DIRECTORY_SEPARATOR.'aApprouver.php');
    }

    // Fonction permettant de valider une recette
    function valider($pdo, $id) {
        // préparation de la requête de mise à jour dans la base de données
        $requete = $pdo->prepare("UPDATE recettes SET isApproved = 1 WHERE id = :id");
        $requete->bindParam(':id', $id);
        
        // exécution de la requête
        $validationOk = $requete->execute();
        
        if($validationOk) {
            $_SESSION['message'] = ['success' => 'Recette validée avec succès'];
            
            // redirection vers la vue de validation effectuée
            header('Location:?c=Recette&a=aApprouver');
        } else {
            $_SESSION['message'] = ['danger' => 'Erreur dans la validation de la recette'];
        }
    }

    // Fonction permettant de compter le nombre de recettes non validées
    function nbAValider($pdo) {
        if(isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']) {
            // préparation de la requête de sélection dans la base de données
            $requete = $pdo->prepare("SELECT COUNT(*) as nbRecettesNonValides FROM recettes WHERE isApproved = 0 OR isApproved IS NULL");
                    
            // exécution de la requête et récupération des données
            $requete->execute();
            $resultat = $requete->fetch(PDO::FETCH_ASSOC);

            echo $resultat['nbRecettesNonValides'];
        } else {
            echo 0;
        }
        
    }

    // Fonction permettant de lister les recettes non valides concernant un utilisateur
    function nonValidesPourUtilisateur($pdo, $idUtilisateur) {
        // préparation de la requête de sélection dans la base de données
        $requete = $pdo->prepare("SELECT r.* FROM recettes r JOIN users u ON r.auteur = u.mail WHERE u.id = :idUtilisateur AND r.isApproved = 0");
        $requete->bindParam(':idUtilisateur', $idUtilisateur);
        
        // exécution de la requête et récupération des données
        $requete->execute();
        $recipes = $requete->fetchAll(PDO::FETCH_ASSOC);
        
        require_once(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Views'.DIRECTORY_SEPARATOR. 'Recette' . DIRECTORY_SEPARATOR.'enCoursValidation.php');
    }
}