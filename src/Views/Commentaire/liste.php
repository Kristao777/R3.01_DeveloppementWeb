<h1>Liste des commentaires</h1>

<div class="row">
    <!-- Boucle permettant de lister les recettes -->
    <?php foreach ($commentaires as $commentaire) : ?>
        <div class="col-4 p-2">
            <a href="?c=Comment&a=supprimer&id=<?php echo $commentaire['id']; ?>"><i class="bi bi-trash"></i></a>
            <!-- Utilisation des Cards Bootstrap -->
            <div class="comment card" data-id="<?php echo $commentaire['id']; ?>">
                <div class="card-body">
                    <p class="card-text">Pseudo : <?php echo $commentaire['pseudo']; ?></p>
                    <p class="card-text">Commentaire : <?php echo $commentaire['commentaire']; ?></p>
                    <p class="card-text">Date de création : <?php echo $commentaire['create_time']; ?></p>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<a href="?c=home" class="btn btn-primary">Retour à l'accueil</a>