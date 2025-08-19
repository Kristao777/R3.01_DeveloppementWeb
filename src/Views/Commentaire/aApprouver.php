<h1>Commentaires à approuver</h1>
<table class="table table-striped">
    <thead>
        <tr>
            <th scope="col">Commentaire</th>
            <th scope="col">Pseudo</th>
            <th scope="col">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($comments as $comment) { ?>
            <tr>
                <td scope="row"><?php echo $comment['commentaire']; ?></td>
                <td><?php echo $comment['pseudo']; ?></td>
                <td>
                    <a href="?c=Commentaire&a=valider&id=<?php echo $comment['id']; ?>" class="btn btn-success" title="Valider le commentaire">Valider</a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>