// Écoute le chargement du DOM
document.addEventListener('DOMContentLoaded', () => {

    // Sélectionne toutes les recettes avec la classe 'recipefav'
    let recipefav = document.querySelectorAll('.recipefav');

    // Ajoute un écouteur d'événements sur chaque recette
    recipefav.forEach(recipe => {
        
        recipe.addEventListener('mouseover', (event) => {
            recipe.style.cursor = 'pointer'; // Change le curseur de la souris en pointeur lorsque la souris passe dessus la recette
        });
        
        recipe.addEventListener('mouseout', (event) => {
            recipe.style.cursor = ''; // Retire le curseur de la souris en pointeur lorsque la souris sort de la recette
        });
        
        recipe.addEventListener('click', (event) => {
            event.preventDefault(); // Empêche le comportement par défaut
            fetch('?c=Favori&a=ajouter&id='+recipe.dataset.id)
            .then(function() {
                location.reload();
            });
        });
    
    });

    // Sélectionne toutes les recettes avec la classe 'recipe'
    let recipes = document.querySelectorAll('.recipe');

    // Ajoute un écouteur d'événements sur chaque recette
    recipes.forEach(recipe => {
        
        recipe.addEventListener('mouseover', (event) => {
            recipe.style.backgroundColor = 'lightgray'; // Ajoute un fond gris lorsque la souris passe dessus la recette 500ms après la survolée 500ms avant l'événement click.
            recipe.style.cursor = 'pointer'; // Change le curseur de la souris en pointeur lorsque la souris passe dessus la recette
        });
        
        recipe.addEventListener('mouseout', (event) => {
            recipe.style.backgroundColor = ''; // Retire le fond gris lorsque la souris sort de la recette
            recipe.style.cursor = ''; // Retire le curseur de la souris en pointeur lorsque la souris sort de la recette
        });
        
        recipe.addEventListener('click', (event) => {
            event.preventDefault(); // Empêche le comportement par défaut
            let recipeId = recipe.dataset.id; // Récupère l'ID de la recette
            //alert(`Détail de la recette : ${recipeId}`); // Affiche une alerte avec l'ID
            window.open('?c=Recette&a=detail&id=' + recipeId,'_self'); // Ouvre le détail de la recette
        });
    
    });

    // Sélectionne toutes les recettes avec la classe 'recipefav'
    let btAjoutCommentaire = document.getElementById('ajoutcomment');
    
    // Ajoute un écouteur d'événements sur le bouton
    btAjoutCommentaire?.addEventListener('click', (event) => {
        event.preventDefault(); // Empêche le comportement par défaut

        let divCommentaire = document.getElementById('divCommentaire');
        
        // Crée un élément <form>
        let formComment = document.createElement('form');
        formComment.method = 'post';
        formComment.action = '?c=Commentaire&a=ajouter&id=' + btAjoutCommentaire.dataset.id;  // Action du formulaire

        // Créer un textarea
        let textarea = document.createElement('textarea');
        textarea.name = 'commentaire';
        textarea.placeholder = 'Saisir le commentaire';
        textarea.rows = '4';
        textarea.classList.add('form-control');
        textarea.required = true;  // Ajoute un attribut required pour vérifier la saisie

        // Crée un bouton submit
        let submitButton = document.createElement('button');
        submitButton.type = 'submit';  // Type de bouton
        submitButton.textContent = 'Valider le commentaire';  // Texte du bouton
        submitButton.classList.add('btn', 'btn-primary');  // Ajoute une classe au bouton

        // Ajoute un div de class mb-3
        let divMessage = document.createElement('div');
        divMessage.classList.add('mb-3');

        divMessage.appendChild(textarea);
        divMessage.appendChild(submitButton);

        // Ajoute les éléments dans le formulaire
        formComment.appendChild(divMessage);

        divCommentaire.prepend(formComment); // Ajoute le formulaire au div commentairev

        btAjoutCommentaire.classList.add('d-none'); // Affiche le div commentaire

    });

});