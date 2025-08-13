<h1>Connexion</h1>
<form action="?c=User&a=connecter" method="post">
    <div class="mb-3">
        <label for="identifiant" class="form-label">Identifiant</label>
        <input type="text" class="form-control" name="identifiant" id="identifiant" required>
    </div>
    <div class="mb-3">
        <label for="pwd" class="form-label">Mot de passe</label>
        <input type="password" class="form-control" name="pwd" id="pwd" required>
    </div>
    <div class="mb-3">
        <button type="submit" class="btn btn-primary" id="connexion">Se connecter</button>
    </div>
</form>