// Écoute le chargement du DOM
document.addEventListener('DOMContentLoaded', () => {

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

});