<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Models' . DIRECTORY_SEPARATOR . 'User.php';

class UserController {

    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }

    // Fonction permettant d'ajouter un nouvel utilisateur
    function inscription() {
        require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . 'User' . DIRECTORY_SEPARATOR . 'inscription.php';
    }

    // Fonction permettant d'enregistrer un nouvel utilisateur
    function enregistrer() {

        // récupération des données de formulaire
        $identifiant = $_POST['identifiant'];
        $mail = $_POST['mail'];
        $pwd = password_hash($_POST['pwd'], PASSWORD_DEFAULT);

        // préparation de la requête d'insertion dans la base de données

        // création de l'utilisateur
        $ajoutOk = $this->userModel->add($identifiant, $pwd, $mail);
        
        if($ajoutOk) {
            // redirection vers la vue d'enregistrement effectué
            require_once(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Views'.DIRECTORY_SEPARATOR. 'User' .DIRECTORY_SEPARATOR.'enregistrement.php');
        } else {
            echo 'Erreur lors de l\'enregistrement de l\'utilisateur.';
        }
    }

    // Fonction permettant de se connecter à l'application
    function connexion() {
        require_once __DIR__. DIRECTORY_SEPARATOR. '..'. DIRECTORY_SEPARATOR. 'Views'. DIRECTORY_SEPARATOR. 'User' . DIRECTORY_SEPARATOR. 'connexion.php';
    }

    // Fonction permettant de vérifier la connexion d'un utilisateur
    function verifieConnexion() {
        // récupération des données de formulaire
        $identifiant = $_POST['identifiant'];
        $pwd = $_POST['pwd'];
        
        // requête de vérification de l'identifiant
        // on cherche l'utilisateur par son identifiant
        // attention au piège, il faut utiliser findBy pour récupérer l'utilisateur
        // car find a besoin de l'id, par contre, findBy renvoie un tableau d'utilisateurs
        // et on prend le alors que le premier utilisateur trouvé d'où le [0]
        $user = $this->userModel->findBy(['identifiant' => $identifiant])[0];
        
        // si l'utilisateur existe et le mot de passe est correct
        if($user && password_verify($pwd, $user['password'])) {
            // définition des variables de session
            $_SESSION['id'] = $user['id'];
            $_SESSION['identifiant'] = $user['identifiant'];
            $_SESSION['mail'] = $user['mail'];
            
            // redirection vers la page d'accueil
            header('Location: ?c=home');
        } else {
            echo 'Identifiant ou mot de passe incorrect.';
        }
    }

    function profil() {
        // récupération des données de l'utilisateur courant
        $id = $_SESSION['id'];
        
        // requête de récupération des données de l'utilisateur
        $user = $this->userModel->find($id);
        
        // affichage du profil de l'utilisateur courant
        require_once __DIR__. DIRECTORY_SEPARATOR. '..'. DIRECTORY_SEPARATOR. 'Views'. DIRECTORY_SEPARATOR. 'User' . DIRECTORY_SEPARATOR. 'profil.php';
    }

    // Fonction permettant de déconnecter un utilisateur
    function deconnexion() {
        // déstruction des variables de session
        session_destroy();
        
        // redirection vers la page de connexion
        header('Location: ?c=home');
    }

}