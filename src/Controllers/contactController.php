<?php

class ContactController {

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

        /** @var PDO $pdo **/
        $requete = $pdo->prepare('INSERT INTO contacts (nom, mail, description, date_creation) VALUES (:nom, :mail, :description, NOW())');
        $requete->bindParam(':nom', $nom);
        $requete->bindParam(':mail', $mail);
        $requete->bindParam(':descriptio', $description);

        // exécution de la requête
        $ajoutOk = $requete->execute();
        
        if($ajoutOk) {
            // redirection vers la vue d'enregistrement effectué
            require_once(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'Views'.DIRECTORY_SEPARATOR. 'Contact' .DIRECTORY_SEPARATOR.'enregistrement.php');
        } else {
            echo 'Erreur lors de l\'enregistrement du contact.';
        }
    }
}
    