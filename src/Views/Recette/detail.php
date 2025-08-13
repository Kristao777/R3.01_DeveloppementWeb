<h1><?php echo $recipe['titre']; ?></h1>
<div class="row">
    <div class="col"><img class="rounded mx-auto img-fluid" src="<?php echo $recipe['image'] != '' ? 'upload'.DIRECTORY_SEPARATOR.$recipe['image'] : 'upload'.DIRECTORY_SEPARATOR.'no_image.png' ;?>" alt="<?php echo $recipe['titre'];?>" class="card-img-top"></div>
    <div class="col">Description : <?php echo $recipe['description']; ?><br>
    Auteur : <a href="mailto:<?php echo $recipe['auteur']; ?>"><?php echo $recipe['auteur']; ?></a></div>
</div>
<p></p>
<?php if(isset($_SESSION['identifiant'])) {?>
    <a href="?c=Recette&a=modifier&id=<?php echo $recipe['id'];?>" class="btn btn-primary">Modifier la recette</a>
    <?php if(!$existe) {?>
        <a href="?c=Favori&a=ajouter&id=<?php echo $recipe['id'];?>" class="btn btn-primary">Ajouter aux favoris</a>
    <?php } else {?>
        <a href="?c=Favori&a=ajouter&id=<?php echo $recipe['id'];?>" class="btn btn-primary">Retirer des favoris</a>
    <?php }?>
<?php } ?>
<a href="#" id="ajoutcomment" data-id="<?php echo $recipe['id'];?>" class="btn btn-primary">Ajouter un commentaire</a>
<a href="?c=Recette&a=index" class="btn btn-primary">Retour à la liste des recettes</a>

<h2>Commentaires</h2>
<div id="divCommentaire">
    <?php if(!$commentaires) { ?>
        <p>Aucun commentaire sur cette recette</p>
    <?php } else { ?>
        <?php foreach($commentaires as $commentaire) : ?>
            <div class="border border-3 border-black rounded rounded-4 p-1">
                <p><?php echo $commentaire['commentaire']; ?></p>
                <p class="text-end"><b>Par <?php echo $commentaire['pseudo']." le ".$commentaire['create_time']; ?></b></p>
            </div>
        <?php endforeach; ?>
    <?php } ?>
</div>