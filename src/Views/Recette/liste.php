<h1>Recettes</h1>

<div class="row">
    <!-- Boucle permettant de lister les recettes -->
    <?php foreach ($recipes as $recipe) : ?>
        <div class="col-4 p-2">
            <?php if(isset($_SESSION['identifiant'])) {?> 
                <a href="?c=Recette&a=modifier&id=<?php echo $recipe['id']; ?>"><i class="bi bi-pencil-square"></i></a>
                <?php $favorisController = new FavoriController(); ?>
                <?php $existe = $favorisController->existe($pdo,$recipe['id'],$_SESSION['id']); ?>
                <?php if(!$existe) { ?>
                    <span class="recipefav"  data-id="<?php echo $recipe['id']; ?>" title="Mettre en favoris"><i class="bi bi-heart"></i></span>
                <?php } else { ?>
                    <span class="recipefav"  data-id="<?php echo $recipe['id']; ?>" title="Retirer des favoris"><i class="bi bi-heart-fill"></i></span>
                <?php } ?>
                <?php if($_SESSION['isAdmin']) : ?>
                    <a href="?c=Recette&a=supprimer&id=<?php echo $recipe['id']; ?>"><i class="bi bi-trash"></i></a>
                <?php endif;?>
            <?php } ?>
            <!-- Utilisation des Cards Bootstrap -->
            <div class="recipe card" data-id="<?php echo $recipe['id']; ?>">
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