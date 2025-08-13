<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Cosina</title>
    <!-- Bootstrap CSS and JS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>  
    <!-- <script src="https://kit.fontawesome.com/75f10c1121.js" crossorigin="anonymous"></script> -->
    <script src="src/Views/js/recipes.js"></script>
    <script src="src/Views/js/users.js"></script>
</head>
<body>
    <!-- menu de navigation -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary justify-content-between">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href='?c=home'>Accueil</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href='?c=Recette&a=index'>Recettes</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href='?c=Contact&a=ajouter'>Contact</a>
            </li>
            <?php if(isset($_SESSION['identifiant'])) {?>
                <div class="vr"></div>                        
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Bienvenue <?php echo $_SESSION['identifiant'];?>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href='?c=User&a=profil'>Mon profil</a></li>
                        <li><a class="nav-link" href='?c=Recette&ajouter'>Ajouter une recette</a></li>
                        <li><a class="nav-link" href='?c=Favori&a=mesFavoris'>Mes recettes favorites</a></li>
                        <?php if($_SESSION['isAdmin']) {?>
                            <li><a class="nav-link" href='?c=Commentaire&a=lister'>Liste des commentaires</a></li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>
        </ul>
        <ul class="navbar-nav">
            <?php if(isset($_SESSION['identifiant'])) { ?>
                <li class="nav-item">
                    <a class="btn btn-outline-dark" href='?c=User&a=deconnexion'>Déconnexion</a>
                </li>
            <?php } else { ?>
                <li class="nav-item">
                    <a class="btn btn-outline-dark" href='?c=User&a=inscription'>Inscription</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-outline-dark" href='?c=User&a=connexion'>Connexion</a>
                </li>
            <?php } ?>
        </ul>
    </nav>
    <div class="container w-75 m-auto">
        <?php if(isset($_SESSION['message'])) : ?>
        <?php foreach ($_SESSION["message"] as $type => $message) { ?>
            <div class="alert alert-<?php echo $type; ?>">
                <?php echo $message; ?>
            </div>
        <?php } endif; unset($_SESSION["message"]); ?>