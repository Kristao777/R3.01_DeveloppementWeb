<h1>Recettes</h1>

<div class="row">
    <!-- Boucle permettant de lister les recettes -->
    <?php foreach ($recipes as $recipe) : ?>
        <div class="col-4 p-2" data-id="<?php echo $recipe['id']; ?>">
            <!-- Utilisation des Cards Bootstrap -->
            <div class="recipe card">
                <div class="card-body">
                    <img src="<?php echo $recipe['image'] != '' ? 'upload'.DIRECTORY_SEPARATOR.$recipe['image'] : 'upload'.DIRECTORY_SEPARATOR.'no_image.png' ;?>" alt="<?php echo $recipe['titre'];?>" class="card-img-top">
                    <h2 class="card-title"><?php echo $recipe['titre']; ?></h2>
                    <p class="card-text"><?php echo $recipe['description']; ?></p>
                    Auteur : <a href="mailto:<?php echo $recipe['auteur']; ?>"><?php echo $recipe['auteur']; ?></a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<a href="?c=home" class="btn btn-primary">Retour à l'accueil</a>
