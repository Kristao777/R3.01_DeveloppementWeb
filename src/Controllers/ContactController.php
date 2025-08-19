<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Models' . DIRECTORY_SEPARATOR . 'Contact.php';

class ContactController {

    private $contactModel;

    public function __construct() {
        $this->contactModel = new Contact();
    }
    function ajouter() {
        // lien vers la vue du formulaire de contact
        require_once __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Views'. DIRECTORY_SEPARATOR. 'Contact' . DIRECTORY_SEPARATOR.'contact.php';
    }

    function enregister($pdo) {
        // récupération des données de formulaire
        $nom = $_POST['nom'];
        $mail = $_POST['mail'];
        $description = $_POST['description'];

        // préparation de la requête d'insertion dans la base de données
        $ajoutOk = $this->contactModel->add($nom, $mail, $description);
        
        if($ajoutOk) {
            // redirection vers la vue d'enregistrement effectué
            require_once(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Views'.DIRECTORY_SEPARATOR. 'Contact' .DIRECTORY_SEPARATOR.'enregistrement.php');
        } else {
            echo 'Erreur lors de l\'enregistrement du contact.';
        }
    }
}
    
