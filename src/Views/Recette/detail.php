<h1><?php echo $recipe['titre']; ?></h1>
<div class="row">
    <div class="col"><img class="rounded mx-auto img-fluid" src="<?php echo $recipe['image'] != '' ? 'upload'.DIRECTORY_SEPARATOR.$recipe['image'] : 'upload'.DIRECTORY_SEPARATOR.'no_image.png' ;?>" alt="<?php echo $recipe['titre'];?>" class="card-img-top"></div>
    <div class="col">Description : <?php echo $recipe['description']; ?><br>
    Auteur : <a href="mailto:<?php echo $recipe['auteur']; ?>"><?php echo $recipe['auteur']; ?></a></div>
</div>
<p></p>
<?php if(isset($_SESSION['identifiant'])) {?>
    <a href="?c=modif&id=<?php echo $recipe['id'];?>" class="btn btn-primary">Modifier la recette</a>
<?php } ?>
<a href="?c=liste" class="btn btn-primary">Retour à la liste des recettes</a>