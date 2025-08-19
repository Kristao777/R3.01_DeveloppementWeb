// Écoute le chargement du DOM
document.addEventListener('DOMContentLoaded', () => {


    /* Ajout partie de l'affichage du profil utilisateur */

    // Sélection du profil identifiant (contenu modifiable)
    let profil_identifiant = document.getElementById('profil_identifiant');
    // Sélection du profil mail (contenu modifiable)
    let profil_mail = document.getElementById('profil_mail');
    // Sélection du bouton de modification
    let modifier_profil = document.getElementById('bouton_modifier_profil');
    
    // Ajoute un écouteur d'événements pour afficher le bouton de modification 
    // lorsque le contenu du profil est modifié
    profil_identifiant?.addEventListener('input', (event) => {
        modifier_profil.classList.remove('d-none'); // Affiche le bouton de modification
    });

    // Ajoute un écouteur d'événements pour afficher le bouton de modification 
    // lorsque le contenu du profil est modifié
    profil_mail?.addEventListener('input', (event) => {
        modifier_profil.classList.remove('d-none'); // Affiche le bouton de modification
    });

    /* Ajout partie de l'affichage des recettes favorites */

    let divFavoris = document.getElementById('favoris');

    // Vérifie si le div contenant les favoris existe
    if(divFavoris) {

        let id_utilisateur = divFavoris.dataset.id; // Remplacer par l'ID de l'utilisateur

        fetch("?c=Favori&a=getFavoris&x&id="+id_utilisateur)
        .then(response => response.json())
        .then(response => {
            JSON.stringify(response);
            divFavoris.innerHTML = "<ul>";
            divFavoris.innerHTML += response.map(favori => {
                return "<li><a href='?c=Recette&a=detail&id="+favori.id+"'>"+favori.titre+"</a></li>";
            }).join("");
            divFavoris.innerHTML += "</ul>";
        })
        .catch(error => console.log("Erreur : " + error));
    }

    // Gestion des notifications en cas de recettes à valider
    fetch("?c=Recette&a=nbAValider&x")
    .then(response => response.text())
    .then(response => {
        if(parseInt(response) > 0) {
            let menuAdmin = document.getElementById("menu-admin");
            let badge = '<span class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle">';
            menuAdmin.innerHTML += badge;
            let badgeDetail = '<span class="badge sticky-top start-100 text-bg-danger">'+response+'</span>';
            let menuAValider = document.getElementById("recette-a-valider");
            menuAValider.innerHTML += badgeDetail;
        }
        
    })

    // Gestion des notifications en cas de commentaires à valider
    fetch("?c=Commentaire&a=nbAValider&x")
    .then(response => response.text())
    .then(response => {
        if(parseInt(response) > 0) {
            let menuAdmin = document.getElementById("menu-admin");
            let badge = '<span class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle">';
            menuAdmin.innerHTML += badge;
            let badgeDetail = '<span class="badge sticky-top start-100 text-bg-danger">'+response+'</span>';
            let menuAValider = document.getElementById("comment-a-valider");
            menuAValider.innerHTML += badgeDetail;
        }
        
    })
    
});